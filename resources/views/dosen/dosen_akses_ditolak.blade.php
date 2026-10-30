<x-app-layout>
    <div class="max-w-xl mx-auto py-20 text-center">
        <h1 class="text-5xl font-bold text-gray-800">403</h1>
        <h2 class="mt-4 text-xl font-semibold">Akses Ditolak</h2>
        <p class="mt-2 text-gray-600">
            Anda tidak memiliki akses ke proposal ini. Proposal hanya dapat
            dilihat oleh ketua dan anggota pengusul.
        </p>
        <a href="{{ url()->previous() }}"
           class="inline-block mt-6 px-4 py-2 bg-blue-600 text-white rounded">
            Kembali
        </a>
    </div>
</x-app-layout>