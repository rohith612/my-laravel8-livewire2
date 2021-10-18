<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;
use Carbon\Carbon;
use App\Models\Comment;

class Comments extends Component
{
    use WithPagination;

    public $newComment, $image, $ticketId = null;

    protected $rules = [
        'newComment' => 'required|min:6|max:50',
    ];
    protected $listeners = [
        'fileUpload' => 'handleFileUpload',
        'ticketSelected' => 'handleTicketSelected'
    ];

    public function handleTicketSelected($ticketId){
        $this->ticketId = $ticketId;
    }

    public function handleFileUpload($imageData){
        $this->image = $imageData;
    }
    
    public function updated($propertyName){
        $this->validateOnly($propertyName);
    }

    public function addComment(){
        $validatedData = $this->validate();
        $image = $this->storeImage();
        $createdComment = Comment::create([
            'body' => $validatedData['newComment'],
            'image' => $image,
            'user_id' => 1,
            'support_ticket_id' => $this -> ticketId
        ]);
        $this->newComment = "";
        $this->image = "";
        session()->flash('message', 'Comment successfully added.');
    }

    public function storeImage(){
        if(!$this->image) return null;
        $img   = ImageManagerStatic::make($this->image)->encode('jpg');
        $name  = Str::random() . '.jpg';
        Storage::disk('public')->put($name, $img);
        return $name;
    }

    public function remove($commentId){
        $comment = Comment::find($commentId);
        Storage::disk('public')->delete($comment->image);
        $comment->delete();
        session()->flash('message', 'Comment successfully removed.');
    }

    public function render(){
        return view('livewire.comments',[
            'comments' =>  Comment::where('support_ticket_id', $this -> ticketId)->latest()->paginate(5)
        ]);
    }
}
