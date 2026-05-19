<div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-4">

        <div>
            <h3 class="text-lg font-bold text-gray-800">
                Top Sales Employee
            </h3>
            <p class="text-xs text-gray-500">
                Performa sales bulan ini
            </p>
        </div>

        <span class="text-xs text-gray-400">
            TOP 10
        </span>

    </div>

    {{-- LIST --}}
    <div class="space-y-3">

        @forelse($employees as $index => $emp)

            @php
                $isTop1 = $index == 0;
            @endphp

            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition">

                {{-- LEFT --}}
                <div class="flex items-center gap-3">

                    {{-- RANK --}}
                    <div class="w-8 h-8 flex items-center justify-center rounded-full font-bold 
                        {{ $isTop1 ? 'bg-yellow-400 text-white shadow-md' : 'bg-gray-200 text-gray-700' }}">

                        @if($isTop1)
                            👑
                        @else
                            {{ $index + 1 }}
                        @endif

                    </div>

                    {{-- NAME --}}
                    <div>
                        <div class="font-semibold text-gray-800">
                            {{ $emp->NamaPegawai }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $emp->KodePegawai }}
                        </div>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="text-right">

                    <div class="font-bold text-gray-800">
                        Rp {{ number_format($emp->TotalPenjualan, 0, ',', '.') }}
                    </div>

                    <div class="text-xs text-gray-500">
                        {{ $emp->TotalTransaksi }} trx • {{ $emp->TotalQty }} qty
                    </div>

                </div>

            </div>

        @empty

            <div class="text-center text-gray-400 py-6">
                Tidak ada data
            </div>

        @endforelse

    </div>
</div>