<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
                    <div class="space-y-6">
                        <div class="rounded-3xl border border-purple-200 bg-purple-50 p-6 shadow-sm">
                            <h2 class="text-xl font-semibold text-purple-900">Account Summary</h2>
                            <p class="mt-2 text-sm text-purple-700">Manage your account details, view reservation status, and keep your QR ready.</p>
                            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl bg-white p-4 shadow-sm">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Name</p>
                                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                                </div>
                                <div class="rounded-2xl bg-white p-4 shadow-sm">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Student ID</p>
                                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $user->student_id }}</p>
                                </div>
                                <div class="rounded-2xl bg-white p-4 shadow-sm">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Email</p>
                                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                                </div>
                                <div class="rounded-2xl bg-white p-4 shadow-sm">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Account Type</p>
                                    <p class="mt-2 text-lg font-semibold text-gray-900">{{ $user->is_admin ? 'Administrator' : 'Student' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900">Pending Reservations</h3>
                                    <p class="text-sm text-slate-500">Reservations waiting for approval or still in progress.</p>
                                </div>
                            </div>
                            <div class="mt-5 space-y-4">
                                @if($pendingReservations->isEmpty())
                                    <p class="text-sm text-slate-600">You don’t have any pending reservations at the moment.</p>
                                @else
                                    @foreach($pendingReservations as $reservation)
                                        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                            <div class="flex items-center justify-between gap-4">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-900">{{ $reservation->facility->room_name }}</p>
                                                    <p class="mt-1 text-sm text-slate-600">{{ $reservation->facility->building }}</p>
                                                </div>
                                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Pending</span>
                                            </div>
                                            <p class="mt-3 text-sm text-slate-600">{{ $reservation->start_time->format('M d, Y H:i') }} — {{ $reservation->end_time->format('M d, Y H:i') }}</p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-3xl border border-purple-200 bg-white p-6 shadow-sm text-center">
                            <h3 class="text-lg font-semibold text-purple-900">My QR Code</h3>
                            <p class="mt-2 text-sm text-purple-600">Use this when you check in at a facility.</p>
                            @if($qrUrl)
                                <img src="{{ $qrUrl }}" alt="Your QR Code" class="mx-auto mt-5 h-44 w-44 rounded-3xl border border-purple-200 bg-purple-50 p-3 shadow-inner" />
                            @else
                                <div class="mt-5 rounded-3xl border border-dashed border-purple-300 bg-purple-50 px-4 py-16 text-sm text-purple-700">QR code is not generated yet.</div>
                            @endif
                            <div class="mt-5 rounded-2xl bg-purple-600 px-4 py-3 text-sm font-semibold text-white">Show this QR at the gate or to facility staff</div>
                        </div>

                        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-slate-900">Reservation History</h3>
                            <p class="mt-2 text-sm text-slate-500">All your recent reservations and their status.</p>
                            <div class="mt-5 space-y-3">
                                @forelse($reservations as $reservation)
                                    <div class="rounded-2xl border border-slate-200 p-4">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="font-semibold text-slate-900">{{ $reservation->facility->room_name }}</p>
                                                <p class="text-sm text-slate-500">{{ $reservation->start_time->format('M d, Y H:i') }} — {{ $reservation->end_time->format('M d, Y H:i') }}</p>
                                            </div>
                                            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $reservation->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($reservation->status === 'rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">{{ ucfirst($reservation->status) }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-600">You have not made any reservations yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
