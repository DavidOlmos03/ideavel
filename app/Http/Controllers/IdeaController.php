<?php

namespace App\Http\Controllers;
use App\Models\Idea;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IdeaController extends Controller
{
    private array $rules = [
        'title'=> 'required|string|max:100',
        'description' => 'required|string|max:300'
    ];

    private array $errorMessages = [
        'title.required'=> 'El campo titulo es obligatorio.',
        'description.required'=> 'El campo descripción es obligatorio.',
        'string' => 'Este campo debe ser de tipo String.',
        'title.max' => 'El campo titulo no debe ser mayor a :max caracteres.',
        'description.max' => 'El campo descripción no debe ser mayor a :max caracteres.'
    ];

    public function index(Request $request):View
    {
        /*$ideas = DB::table('ideas')->get();  select * from ideas   Obs. Esto no me serviria en este caso debido a que al intentar
                                                    utilizarlo en index.blade.php lo estoy llamando como si fuera un orm de laravel llamado eloquent*/

        $ideas = Idea::myIdeas($request->filtro)->theBest($request->filtro)->get();  //En este caso para aplicar uno de los dos filtros, pero no ambos al mismo tiempo
        return view('ideas.index',['ideas'=>$ideas]);
    }

    public function create():View
    {
        return view('ideas.create_or_edit');
    }

    public function store(Request $request): RedirectResponse
    {

        $validated = $request->validate($this->rules, $this->errorMessages);

        //$request->all()     esto seria para solicitar todos los datos de la idea
        Idea::create([
            'user_id' => auth()->user()->id,      //$request->user()->id    ie se obtiene el id del usuario autenticado
            'title'=>$validated['title'],
            'description'=>$validated['description']
        ]);

        session()->flash('message','Idea creada correctamente!');
        return redirect()->route('idea.index');
    }

    public function edit(Idea $idea): View
    {
        $this -> authorize('update', $idea);
        return view('ideas.create_or_edit')->with('idea', $idea);
    }

    public function update(Request $request,Idea $idea): RedirectResponse
    {
        $this -> authorize('update', $idea);  //Mediante la pabra authorize va a policies/IdeaPolicy.php, busca update y le pasa los parametros necesarios
        $validated = $request->validate($this->rules, $this->errorMessages);

        $idea->update($validated);

        session()->flash('message','Idea atualizada correctamente!');
        return redirect(route('idea.index'));
    }

    public function show(Idea $idea): View
    {
        return view('ideas.show')->with('idea',$idea);
    }

    public function delete(Idea $idea): RedirectResponse
    {
        $this -> authorize('delete', $idea);
        $idea->delete();
        session()->flash('message','Idea eliminada correctamente!');
        return redirect()->route('idea.index');
    }


    public function synchronizeLikes(Request $request,Idea $idea): RedirectResponse
    {
        $this->authorize('updateLikes',$idea);
        $request->user()->ideasLiked()->toggle([$idea->id]);    //forma 1

        //$idea -> users()->toggle([$request->user()->id]);   forma 2, funcionan exactamente igual

        $idea->update(['likes' => $idea->users()->count()]);

        return redirect()->route('idea.show',$idea);
    }
}
