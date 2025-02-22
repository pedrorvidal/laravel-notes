<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MainController extends Controller
{
    public function index()
    {
        // load user's notes
        $id = session('user.id');
        $notes = User::find($id)
            ->notes()
            ->whereNull('deleted_at')
            ->get()
            ->toArray();

        // show home view
        return view('home', ['notes' => $notes]);
    }

    public function newNote()
    {
        // show new note view
        return view('new_note');
    }
    public function newNoteSubmit(Request $request)
    {
        // save new note
        // echo "I'm creating a new note";

        // TODO: validate request
        $request->validate(
            //rules
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:6|max:3000'
            ],
            //error messages
            [
                'text_title.required' => 'O título é obrigatório',
                'text_title.min' => 'O título deve ter pelo menos :min caracteres',
                'text_title.max' => 'O título deve ter no máximo :max caracteres',

                'text_note.required' => 'A nota é obrigatória',
                'text_note.min' => 'A nota deve ter pelo menos :min caracteres',
                'text_note.max' => 'A nota deve ter no máximo :max caracteres',
            ]
        );

        // TODO: get user indexcreate new note
        $id = session('user.id');

        // TODO: get user indexcreate new note
        $note = new Note();
        $note->user_id = $id;
        $note->title = $request->text_title;
        $note->text = $request->text_note;

        $note->save();
        // TODO: redirect to home
        return redirect()->route('home');
    }
    public function editNote($id)
    {
        // $id = $this->decryptId($id);
        $id = Operations::decryptId($id);

        //TODO: carregar a nota a ser editada
        $note = Note::find($id);
        return view('edit_note', ['note' => $note]);
    }
    public function editNoteSubmit(Request $request)
    {
        // TODO: validate request
        $request->validate(
            //rules
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:6|max:3000'
            ],
            //error messages
            [
                'text_title.required' => 'O título é obrigatório',
                'text_title.min' => 'O título deve ter pelo menos :min caracteres',
                'text_title.max' => 'O título deve ter no máximo :max caracteres',

                'text_note.required' => 'A nota é obrigatória',
                'text_note.min' => 'A nota deve ter pelo menos :min caracteres',
                'text_note.max' => 'A nota deve ter no máximo :max caracteres',
            ]
        );
        //TODO: check if note exists
        if ($request->note_id == null) {
            return redirect()->route('home');
        }
        // TODO: decrypt note_id
        $id = Operations::decryptId($request->note_id);
        // TODO: load note
        $note = Note::find($id);
        // TODO: update note
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        // TODO: redirect to home
        return redirect()->route('home');
    }

    public function deleteNote($id)
    {
        $id = Operations::decryptId($id);
        // TODO:
        $note = Note::find($id);
        // TODO: show delete note confirmation
        return view('delete_note', ['note' => $note]);
    }

    public function deleteNoteConfirm($id)
    {
        $id = Operations::decryptId($id);
        $note = Note::find($id);

        // 1: hard delete
        // $note->delete();

        // 2: soft delete
        // $note->deleted_at = date('Y:m:d H:i:s');
        // $note->save();

        //3: soft delete (property in model)
        $note->delete();

        return redirect()->route('home');
    }
}
