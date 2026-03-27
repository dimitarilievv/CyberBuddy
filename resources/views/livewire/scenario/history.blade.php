<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6">Scenario Attempts History</h1>

    @if($attempts->isEmpty())
        <div class="bg-gray-100 rounded-lg p-8 text-center">
            <p class="text-gray-600">No attempts yet.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                <tr class="bg-blue-100">
                    <th class="border p-3 text-left">Scenario</th>
                    <th class="border p-3 text-left">Score</th>
                    <th class="border p-3 text-left">Percentage</th>
                    <th class="border p-3 text-left">Status</th>
                    <th class="border p-3 text-left">Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($attempts as $attempt)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-3">{{ $attempt->scenario->title }}</td>
                        <td class="border p-3">{{ $attempt->total_score }}/{{ $attempt->max_score }}</td>
                        <td class="border p-3">{{ $attempt->percentage }}%</td>
                        <td class="border p-3">
                                <span class="px-3 py-1 rounded-full text-sm font-bold {{ $attempt->percentage >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $attempt->percentage >= 70 ? 'PASSED' : 'FAILED' }}
                                </span>
                        </td>
                        <td class="border p-3">{{ $attempt->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $attempts->links() }}
        </div>
    @endif
</div>
