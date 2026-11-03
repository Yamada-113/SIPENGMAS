<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Services\NotificationFlowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProposalController extends Controller
{
    public function approve(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        $user = Auth::user();
        $currentStatus = $proposal->status_progress;

        DB::beginTransaction();
        try {
            if ($currentStatus == 1) {
                if ($user->role !== 'Wakil Dekan 3') {
                    return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses.'], 403);
                }
                if ($proposal->skala_pelaksanaan == 'Prodi') {
                    $proposal->status_progress = 3;
                } else {
                    $proposal->status_progress = 2;
                }
                $proposal->save();
                NotificationFlowService::wadekApproved($proposal);
            } elseif ($currentStatus == 2) {
                $requiredRole = 'Kepala Pusat ' . $proposal->skema;
                if ($user->role !== $requiredRole && $user->role !== 'Admin') { // Admin bisa bypass jika perlu
                    return response()->json(['success' => false, 'message' => 'Anda bukan Kepala Pusat yang sesuai untuk skema ini.'], 403);
                }
                $proposal->status_progress = 3;
                $proposal->save();
                NotificationFlowService::pusatApproved($proposal);
            } elseif ($currentStatus == 3) {
                if ($user->role !== 'Wakil Rektor 3') {
                    return response()->json(['success' => false, 'message' => 'Hanya Wakil Rektor 3 yang dapat memvalidasi.'], 403);
                }
                $proposal->status_progress = 4;
                $proposal->save();
                NotificationFlowService::warekApproved($proposal);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Proposal sudah mencapai tahap akhir atau status tidak valid.'
                ], 400);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Proposal berhasil divalidasi.',
                'data'    => $proposal
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error Approve Proposal: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string|max:1000'
        ]);
        $proposal = Proposal::findOrFail($id);
        $user = Auth::user();
        $canReject = false;
        if ($proposal->status_progress == 1 && $user->role == 'Wakil Dekan 3') $canReject = true;
        if ($proposal->status_progress == 2 && str_contains($user->role, 'Kepala Pusat')) $canReject = true;
        if ($proposal->status_progress == 3 && $user->role == 'Wakil Rektor 3') $canReject = true;
        if (!$canReject) {
            return response()->json(['success' => false, 'message' => 'Anda tidak berwenang menolak proposal pada tahap ini.'], 403);
        }
        DB::beginTransaction();
        try {
            $proposal->status_progress = 99;
            $proposal->feedback = $request->feedback;
            $proposal->save();
            NotificationFlowService::proposalRejected($proposal, $request->feedback, $user->role);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Proposal berhasil ditolak.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak proposal.'
            ], 500);
        }
    }
}
