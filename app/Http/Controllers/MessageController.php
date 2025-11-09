<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Intern;
use App\Models\User;

class MessageController extends Controller
{
    public function index()
    {
        $interns = Intern::where('status', 'accepted')
            ->where('invited_by_user_id', Auth::id())
            ->get();

        foreach ($interns as $intern) {
            $intern->unread_count = Message::where('sender_id', $intern->id)
                ->where('receiver_id', Auth::id())
                ->where('sender_type', 'intern')
                ->where('receiver_type', 'admin')
                ->where('is_read', false)
                ->count();
        }

        return view('messages', compact('interns'));
    }

    public function conversation($internId)
    {
        $adminId = Auth::id();
        $intern = Intern::where('id', $internId)
            ->where('invited_by_user_id', $adminId)
            ->firstOrFail();

        // Mark intern messages as read
        Message::where('sender_id', $internId)
            ->where('receiver_id', $adminId)
            ->where('sender_type', 'intern')
            ->where('receiver_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($q) use ($adminId, $internId) {
            $q->where('sender_id', $adminId)
              ->where('receiver_id', $internId)
              ->where('sender_type', 'admin')
              ->where('receiver_type', 'intern');
        })->orWhere(function ($q) use ($adminId, $internId) {
            $q->where('sender_id', $internId)
              ->where('receiver_id', $adminId)
              ->where('sender_type', 'intern')
              ->where('receiver_type', 'admin');
        })->orderBy('created_at')->get();

        foreach ($messages as $message) {
            $message->sender_name = $message->sender_type === 'admin'
                ? User::find($message->sender_id)?->name ?? 'Admin'
                : Intern::find($message->sender_id)?->first_name . ' ' . Intern::find($message->sender_id)?->last_name;
        }

        return view('conversation', compact('messages', 'intern'));
    }

    public function sendToIntern(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:interns,id',
            'content' => 'required|string',
        ]);

        $admin = Auth::user();
        
        // Ensure the admin can only send messages to their own interns
        $intern = Intern::where('id', $request->receiver_id)
            ->where('invited_by_user_id', $admin->id)
            ->firstOrFail();
            
        $message = Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $request->receiver_id,
            'sender_type' => 'admin',
            'receiver_type' => 'intern',
            'content' => $request->content,
            'is_read' => false,
        ]);

        $message->sender_name = $admin->name;

        if ($request->expectsJson()) {
            return response()->json($message);
        }

        return back()->with('success', 'Message sent.');
    }

    public function broadcast(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $admin = Auth::user();
        $interns = Intern::where('status', 'accepted')
            ->where('invited_by_user_id', $admin->id)
            ->get();

        // Create a single broadcast message
        Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => null, // No specific receiver for broadcast
            'sender_type' => 'admin',
            'receiver_type' => 'all', // Indicates broadcast to all interns
            'content' => $request->content,
            'is_read' => false,
        ]);

        return back()->with('success', '📢 Broadcast message sent to all your interns.');
    }

    public function clearConversation($internId)
    {
        $adminId = Auth::id();
        
        // Ensure the admin can only clear conversations with their own interns
        $intern = Intern::where('id', $internId)
            ->where('invited_by_user_id', $adminId)
            ->firstOrFail();

        Message::where(function ($q) use ($adminId, $internId) {
            $q->where('sender_id', $adminId)
              ->where('receiver_id', $internId)
              ->where('sender_type', 'admin')
              ->where('receiver_type', 'intern');
        })->orWhere(function ($q) use ($adminId, $internId) {
            $q->where('sender_id', $internId)
              ->where('receiver_id', $adminId)
              ->where('sender_type', 'intern')
              ->where('receiver_type', 'admin');
        })->delete();

        return back()->with('success', 'Conversation cleared.');
    }

    public function internMessages()
    {
        $intern = Auth::guard('intern')->user();
        // Determine the owning admin for this intern
        $admin = \App\Models\User::find($intern?->invited_by_user_id);

        if (!$admin) {
            return redirect()->route('intern.dashboard')->with('error', 'Admin not found.');
        }

        // Get all messages (direct messages + broadcast messages)
        $messages = Message::where(function ($q) use ($admin, $intern) {
            // Direct messages between admin and intern
            $q->where(function ($subQ) use ($admin, $intern) {
                $subQ->where('sender_id', $admin->id)
                     ->where('receiver_id', $intern->id)
                     ->where('sender_type', 'admin')
                     ->where('receiver_type', 'intern');
            })->orWhere(function ($subQ) use ($admin, $intern) {
                $subQ->where('sender_id', $intern->id)
                     ->where('receiver_id', $admin->id)
                     ->where('sender_type', 'intern')
                     ->where('receiver_type', 'admin');
            });
        })->orWhere(function ($q) use ($admin) {
            // Broadcast messages from admin to all interns
            $q->where('sender_id', $admin->id)
              ->where('sender_type', 'admin')
              ->where('receiver_type', 'all');
        })->orderBy('created_at')->get();

        // Mark admin messages as read
        Message::where('sender_id', $admin->id)
            ->where('receiver_id', $intern->id)
            ->where('sender_type', 'admin')
            ->where('receiver_type', 'intern')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Calculate stats
        $unreadCount = Message::where('sender_id', $admin->id)
            ->where('receiver_id', $intern->id)
            ->where('sender_type', 'admin')
            ->where('receiver_type', 'intern')
            ->where('is_read', false)
            ->count();

        $lastActivity = $messages->count() > 0 
            ? $messages->last()->created_at->format('M j, Y g:i A')
            : null;

        foreach ($messages as $message) {
            $message->sender_name = $message->sender_type === 'admin'
                ? $admin->name
                : $intern->first_name . ' ' . $intern->last_name;
        }

        return view('intern-messages-enhanced', compact('messages', 'admin', 'unreadCount', 'lastActivity'));
    }

    public function sendFromIntern(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $intern = Auth::guard('intern')->user();
        $admin = \App\Models\User::find($intern?->invited_by_user_id);

        if (!$intern) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 500);
        }

        $message = Message::create([
            'sender_id' => $intern->id,
            'receiver_id' => $admin->id,
            'sender_type' => 'intern',
            'receiver_type' => 'admin',
            'content' => $request->message,
            'is_read' => false,
        ]);

        $message->sender_name = $intern->first_name . ' ' . $intern->last_name;

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => 'Message sent successfully!'
            ]);
        }

        return redirect()->route('intern.messages')->with('success', 'Message sent.');
    }

    // API endpoint to get new messages for real-time updates
    public function getNewMessages(Request $request, $internId)
    {
        $adminId = Auth::id();
        $lastMessageId = $request->get('last_message_id', 0);

        $messages = Message::where('sender_id', $internId)
            ->where('receiver_id', $adminId)
            ->where('sender_type', 'intern')
            ->where('receiver_type', 'admin')
            ->where('id', '>', $lastMessageId)
            ->orderBy('created_at')
            ->get();

        $intern = Intern::find($internId);

        foreach ($messages as $message) {
            $message->sender_name = $intern->first_name . ' ' . $intern->last_name;
        }

        return response()->json(['messages' => $messages]);
    }

    // API endpoint for intern to get new messages
    public function getNewInternMessages(Request $request)
    {
        $intern = Auth::guard('intern')->user();
        $admin = User::first();
        $lastMessageId = $request->get('last_message_id', 0);

        if (!$intern || !$admin) {
            return response()->json(['messages' => []]);
        }

        // Get new direct messages from admin and broadcast messages
        $messages = Message::where(function ($q) use ($admin, $intern) {
            // Direct messages from admin to this intern
            $q->where('sender_id', $admin->id)
              ->where('receiver_id', $intern->id)
              ->where('sender_type', 'admin')
              ->where('receiver_type', 'intern');
        })->orWhere(function ($q) use ($admin) {
            // Broadcast messages from admin to all interns
            $q->where('sender_id', $admin->id)
              ->where('sender_type', 'admin')
              ->where('receiver_type', 'all');
        })
        ->where('id', '>', $lastMessageId)
        ->orderBy('created_at')
        ->get();

        foreach ($messages as $message) {
            $message->sender_name = $admin->name;
        }

        return response()->json(['messages' => $messages]);
    }

    // API endpoint for intern message stats
    public function getInternMessageStats()
    {
        $intern = Auth::guard('intern')->user();
        $admin = User::first();

        if (!$intern || !$admin) {
            return response()->json(['stats' => null]);
        }

        // Count total messages (direct + broadcast)
        $totalMessages = Message::where(function ($q) use ($admin, $intern) {
            $q->where(function ($subQ) use ($admin, $intern) {
                $subQ->where('sender_id', $admin->id)
                     ->where('receiver_id', $intern->id)
                     ->where('sender_type', 'admin')
                     ->where('receiver_type', 'intern');
            })->orWhere(function ($subQ) use ($admin, $intern) {
                $subQ->where('sender_id', $intern->id)
                     ->where('receiver_id', $admin->id)
                     ->where('sender_type', 'intern')
                     ->where('receiver_type', 'admin');
            });
        })->orWhere(function ($q) use ($admin) {
            $q->where('sender_id', $admin->id)
              ->where('sender_type', 'admin')
              ->where('receiver_type', 'all');
        })->count();

        // Count unread messages from admin
        $unreadCount = Message::where('sender_id', $admin->id)
            ->where('receiver_id', $intern->id)
            ->where('sender_type', 'admin')
            ->where('receiver_type', 'intern')
            ->where('is_read', false)
            ->count();

        // Get last activity
        $lastMessage = Message::where(function ($q) use ($admin, $intern) {
            $q->where(function ($subQ) use ($admin, $intern) {
                $subQ->where('sender_id', $admin->id)
                     ->where('receiver_id', $intern->id)
                     ->where('sender_type', 'admin')
                     ->where('receiver_type', 'intern');
            })->orWhere(function ($subQ) use ($admin, $intern) {
                $subQ->where('sender_id', $intern->id)
                     ->where('receiver_id', $admin->id)
                     ->where('sender_type', 'intern')
                     ->where('receiver_type', 'admin');
            });
        })->orWhere(function ($q) use ($admin) {
            $q->where('sender_id', $admin->id)
              ->where('sender_type', 'admin')
              ->where('receiver_type', 'all');
        })->latest()->first();

        $lastActivity = $lastMessage 
            ? $lastMessage->created_at->format('M j, Y g:i A')
            : 'No messages yet';

        return response()->json([
            'stats' => [
                'total' => $totalMessages,
                'unread' => $unreadCount,
                'lastActivity' => $lastActivity
            ]
        ]);
    }
}
