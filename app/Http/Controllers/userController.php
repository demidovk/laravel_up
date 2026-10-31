<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class userController extends Controller
{
    public function showUser()
    {
        $applications=Application::where('user_id', auth()->id())->get();
        return view('User', compact('applications'));

        
    }

    public function showCreateApplication()
    {
        return view('createApplication');
    }

    public function storeCreateApplication(request $request)
    {
        $password=Hash::make($request->password);

        $imagePath=null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('applications', 'public');
        }

        Application::create([
            'name'=>$request->name,
            'address'=>$request->address,
            'another_type'=>$request->another_type,
            'problem'=>$request->problem,
            'date'=>$request->date,
            'time'=>$request->time,
            'status'=>'На модерации'
        ]);
        
        return redirect()->route('show.user')->with('success', 'Заявка успешно создана');
    }

    public function updateApplication(request $request, $id){


        $application = Application::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        return view('updateApplication', compact('application'));
    }

    public function storeUpdateApp(request $request, $id){


        $application = Application::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('applications', 'public');
        }

        $application->update([
            'title'=>$request->title,
            'image'=>$imagePath,
            'description'=>$request->description
        ]);

        return redirect()->route('show.user')->with('success', 'Заявка успешно обновлена!');
    }

    public function destroy(Request $request, int $id)
    {
        $application = Application::findOrFail($id);

        // Проверка: удалять может только владелец заявки
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Вы не можете удалять чужие заявки');
        }

        $application->delete();

        return redirect()->route('show.user')->with('success', 'Заявка успешно удалена');
    }
}
