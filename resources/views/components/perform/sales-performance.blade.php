<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">

    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-800">

        <h2 class="text-sm font-bold text-gray-900 dark:text-white">
            Sales Performance
        </h2>

        <p class="text-xs text-gray-500 dark:text-gray-400">
            Progress penjualan sales bulan berjalan
        </p>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full text-xs">

            <thead class="bg-gray-50 dark:bg-gray-800/60">

                <tr class="uppercase tracking-wide text-gray-500 dark:text-gray-400">

                    <th class="px-3 py-2 text-left font-semibold whitespace-nowrap">
                        Sales
                    </th>

                    <th class="px-2 py-2 text-center font-semibold whitespace-nowrap">
                        LM
                    </th>

                    <th class="px-2 py-2 text-center font-semibold whitespace-nowrap">
                        CM
                    </th>

                    <th class="px-2 py-2 text-center font-semibold whitespace-nowrap">
                        Target
                    </th>

                    <th class="px-2 py-2 text-center font-semibold whitespace-nowrap">
                        %
                    </th>

                    <th class="px-3 py-2 text-right font-semibold whitespace-nowrap">
                        Sales
                    </th>

                    <th class="px-3 py-2 text-right font-semibold whitespace-nowrap">
                        Broker
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">

                @forelse ($sales as $item)

                    @php
                        $achieve = (float) $item['achievement_percent'];

                        $progressColor = match (true) {
                            $achieve >= 100 => 'bg-green-500',
                            $achieve >= 70 => 'bg-yellow-500',
                            default => 'bg-red-500',
                        };
                    @endphp

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/40 transition">

                        <td class="px-3 py-2 whitespace-nowrap">

                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $item['employee_name'] }}
                            </div>

                        </td>

                        <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300 whitespace-nowrap">
                            {{ number_format($item['last_month_units']) }}
                        </td>

                        <td class="px-2 py-2 text-center font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                            {{ number_format($item['current_month_units']) }}
                        </td>

                        <td class="px-2 py-2 text-center text-gray-700 dark:text-gray-300 whitespace-nowrap">
                            {{ number_format($item['target']) }}
                        </td>

                        <td class="px-2 py-2 whitespace-nowrap">

                            <div class="flex items-center gap-2 min-w-[90px]">

                                <span class="w-10 text-right font-semibold text-gray-900 dark:text-white">
                                    {{ number_format($achieve, 0) }}%
                                </span>

                                <div class="flex-1 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">

                                    <div
                                        class="h-1.5 rounded-full {{ $progressColor }}"
                                        style="width: {{ min($achieve, 100) }}%">
                                    </div>

                                </div>

                            </div>

                        </td>

                        <td class="px-3 py-2 text-right font-semibold text-gray-900 dark:text-white whitespace-nowrap">
                            {{ number_format($item['monthly_sales'], 0, ',', '.') }}
                        </td>

                        <td class="px-3 py-2 text-right text-green-600 dark:text-green-400 font-semibold whitespace-nowrap">
                            {{ number_format($item['broker_fee'], 0, ',', '.') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="px-4 py-6 text-center text-xs text-gray-500 dark:text-gray-400">

                            Tidak ada data sales performance.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>
</div>