<div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-4">

        <div>
            <h3 class="text-lg font-bold text-gray-800">
                Monthly Sales All Branch
            </h3>
            <p class="text-xs text-gray-500">
                Ranking penjualan semua cabang bulan ini
            </p>
        </div>

        <span class="text-xs text-gray-400">
            {{ date('F Y') }}
        </span>

    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead>
                <tr class="text-left text-gray-500 border-b">
                    <th class="py-2">#</th>
                    <th>Cabang</th>
                    <th class="text-right">Transaksi</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Penjualan</th>
                </tr>
            </thead>

            <tbody>

                @forelse($branches as $i => $branch)
                    <tr class="border-b last:border-none">

                        {{-- RANK --}}
                        <td class="py-2 font-semibold text-gray-700">
                            {{ $i + 1 }}
                        </td>

                        {{-- CABANG --}}
                        <td class="font-medium text-gray-800">
                            {{ $branch->NamaDealer }}
                        </td>

                        {{-- TRANSAKSI --}}
                        <td class="text-right text-gray-600">
                            {{ $branch->TotalTransaksi }}
                        </td>

                        {{-- QTY --}}
                        <td class="text-right text-gray-600">
                            {{ $branch->TotalQty }}
                        </td>

                        {{-- SALES --}}
                        <td class="text-right font-semibold text-gray-800">
                            Rp {{ number_format($branch->TotalPenjualan, 0, ',', '.') }}
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-400">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

</div>