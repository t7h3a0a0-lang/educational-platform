<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // Admin list of payments
    public function index()
    {
        $payments = Payment::with(['student.user', 'enrollment.course'])->orderBy('created_at', 'desc')->get();
        return view('admin.payments.index', compact('payments'));
    }

    // Show student payment form
    public function showPaymentForm($enrollment_id)
    {
        $enrollment = Enrollment::with('course')->findOrFail($enrollment_id);
        
        // Safety checks
        if ($enrollment->student_id !== Auth::user()->student->id) {
            abort(403, 'غير مصرح لك بالدفع لهذا الملف.');
        }

        if ($enrollment->payment_status === 'paid') {
            return redirect()->route('student.dashboard')->with('error', 'تم سداد رسوم هذا المقرر بالفعل.');
        }

        // Standard course fee: e.g. 150.00 USD per credit
        $amount = $enrollment->course->credits * 150.00;

        return view('student.payments.pay', compact('enrollment', 'amount'));
    }

    // Process simulated payment
    public function processPayment(Request $request, $enrollment_id)
    {
        $enrollment = Enrollment::with('course')->findOrFail($enrollment_id);
        $student = Auth::user()->student;

        if ($enrollment->student_id !== $student->id) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|string|in:credit_card,paypal,bank_transfer',
            'card_number' => 'required_if:payment_method,credit_card|string|min:16|max:16',
            'amount' => 'required|numeric|min:1',
        ]);

        // Create transaction ID
        $transactionId = 'TXN-' . strtoupper(Str::random(10));

        // Create payment record
        $payment = Payment::create([
            'enrollment_id' => $enrollment->id,
            'student_id' => $student->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => $transactionId,
            'status' => 'completed',
            'payment_date' => now(),
        ]);

        // Update enrollment payment status
        $enrollment->update([
            'payment_status' => 'paid',
            'status' => 'approved', // Auto-approve course registration once paid
        ]);

        // Send Notification
        Notification::create([
            'user_id' => Auth::id(),
            'title' => '💳 تم سداد الرسوم بنجاح',
            'message' => 'تم استلام مبلغ ' . $request->amount . ' ريال مقابل رسوم مقرر: ' . $enrollment->course->title . '. رقم المعاملة: ' . $transactionId,
            'type' => 'payment',
        ]);

        return redirect()->route('student.dashboard')->with('success', 'تمت عملية الدفع بنجاح وتفعيل تسجيل المقرر الدراسي!');
    }
}
