<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Simple Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Notiflix CDN (Modern Alert Library) -->
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.7/dist/notiflix-aio-3.2.7.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen text-gray-800 p-6 md:p-12">

    <div class="max-w-3xl mx-auto">

        <!-- Header Section -->
        <header class="mb-8 flex justify-between items-end border-b border-gray-200 pb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tugas Saya</h1>
                <p class="text-gray-500 mt-1 text-sm">Fokus pada hal yang penting.</p>
            </div>
            <div class="text-right">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total</span>
                <div class="text-2xl font-bold text-blue-600 leading-none" id="total-count">{{ $todos->count() }}</div>
            </div>
        </header>

        <!-- Input Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2 mb-8">
            <form id="todo-form" class="flex gap-2">
                <div class="relative flex-1">
                    <input type="text" id="todo-input"
                        class="w-full bg-transparent text-gray-700 text-lg px-4 py-3 focus:outline-none placeholder-gray-400"
                        placeholder="Tulis tugas baru disini..." autocomplete="off" required>
                </div>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 flex items-center shadow-sm">
                    Tambah
                </button>
            </form>
        </div>

        <!-- List Section -->
        <ul id="todo-list" class="space-y-3">
            @foreach ($todos as $todo)
                <li id="todo-{{ $todo->id }}"
                    class="group bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between transition-all hover:shadow-md hover:border-blue-200">
                    <div class="flex items-center gap-4 flex-1">
                        <!-- Checkbox Button -->
                        <button onclick="toggleTodo({{ $todo->id }})"
                            class="w-6 h-6 rounded border flex items-center justify-center transition-all duration-200 
                            {{ $todo->is_completed ? 'bg-blue-600 border-blue-600' : 'border-gray-300 hover:border-blue-500 bg-white' }}">
                            <i class="fas fa-check text-white text-xs {{ $todo->is_completed ? '' : 'hidden' }}"></i>
                        </button>

                        <!-- Text -->
                        <span id="text-{{ $todo->id }}"
                            class="text-base cursor-pointer select-none transition-colors {{ $todo->is_completed ? 'line-through text-gray-400' : 'text-gray-700' }}"
                            onclick="toggleTodo({{ $todo->id }})">
                            {{ $todo->title }}
                        </span>
                    </div>

                    <!-- Delete Button -->
                    <button onclick="deleteTodo({{ $todo->id }})"
                        class="text-gray-400 hover:text-red-500 p-2 transition-colors opacity-0 group-hover:opacity-100">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </li>
            @endforeach
        </ul>

        <!-- Empty State -->
        <div id="empty-state" class="{{ $todos->count() > 0 ? 'hidden' : '' }} text-center py-16">
            <div class="text-gray-200 text-5xl mb-4"><i class="fas fa-check-circle"></i></div>
            <p class="text-gray-400">Semua tugas selesai, atau belum dimulai?</p>
        </div>

    </div>

    <!-- JavaScript Logic -->
    <script>
        Notiflix.Notify.init({
            fontFamily: 'Inter',
            borderRadius: '8px',
            position: 'right-top',
            success: {
                background: '#2563eb',
            },
            failure: {
                background: '#ef4444',
            },
        });

        Notiflix.Confirm.init({
            titleColor: '#1f2937',
            okButtonBackground: '#ef4444',
            cancelButtonBackground: '#9ca3af',
            fontFamily: 'Inter',
            borderRadius: '10px',
            titleFontSize: '18px',
        });

        const todoList = document.getElementById('todo-list');
        const todoInput = document.getElementById('todo-input');
        const emptyState = document.getElementById('empty-state');
        const totalCountEl = document.getElementById('total-count');

        function updateTotal() {
            const count = todoList.children.length;
            totalCountEl.innerText = count;
            if (count === 0) emptyState.classList.remove('hidden');
            else emptyState.classList.add('hidden');
        }

        document.getElementById('todo-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const title = todoInput.value;
            if (!title) return;

            try {
                const res = await fetch("{{ route('todos.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        title: title
                    })
                });

                if (res.ok) {
                    const todo = await res.json();
                    appendTodoToDOM(todo);
                    todoInput.value = '';
                    updateTotal();
                    Notiflix.Notify.success('Tugas berhasil ditambahkan');
                }
            } catch (err) {
                console.error(err);
                Notiflix.Notify.failure('Gagal menambah tugas!');
            }
        });

        function appendTodoToDOM(todo) {
            const li = document.createElement('li');
            li.id = `todo-${todo.id}`;
            li.className =
                "fade-in group bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between transition-all hover:shadow-md hover:border-blue-200";

            li.innerHTML = `
                <div class="flex items-center gap-4 flex-1">
                    <button onclick="toggleTodo(${todo.id})" 
                        class="w-6 h-6 rounded border flex items-center justify-center transition-all duration-200 border-gray-300 hover:border-blue-500 bg-white">
                        <i class="fas fa-check text-white text-xs hidden"></i>
                    </button>
                    <span id="text-${todo.id}" class="text-base cursor-pointer select-none text-gray-700" onclick="toggleTodo(${todo.id})">
                        ${todo.title}
                    </span>
                </div>
                <button onclick="deleteTodo(${todo.id})" class="text-gray-400 hover:text-red-500 p-2 transition-colors opacity-0 group-hover:opacity-100">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;

            todoList.prepend(li);
        }

        async function toggleTodo(id) {
            try {
                const res = await fetch(`/todos/${id}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                });

                if (res.ok) {
                    const data = await res.json();
                    const li = document.getElementById(`todo-${id}`);
                    const btn = li.querySelector('button');
                    const icon = btn.querySelector('i');
                    const text = document.getElementById(`text-${id}`);

                    if (data.is_completed) {
                        btn.classList.remove('border-gray-300', 'hover:border-blue-500', 'bg-white');
                        btn.classList.add('bg-blue-600', 'border-blue-600');
                        icon.classList.remove('hidden');
                        text.classList.add('line-through', 'text-gray-400');
                        text.classList.remove('text-gray-700');
                    } else {
                        btn.classList.add('border-gray-300', 'hover:border-blue-500', 'bg-white');
                        btn.classList.remove('bg-blue-600', 'border-blue-600');
                        icon.classList.add('hidden');
                        text.classList.remove('line-through', 'text-gray-400');
                        text.classList.add('text-gray-700');
                    }
                }
            } catch (err) {
                console.error(err);
            }
        }

        async function deleteTodo(id) {
            Notiflix.Confirm.show(
                'Hapus Tugas?',
                'Tugas ini akan dihapus secara permanen.',
                'Ya, Hapus',
                'Batal',
                async function() {
                        try {
                            const res = await fetch(`/todos/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                }
                            });

                            if (res.ok) {
                                const item = document.getElementById(`todo-${id}`);
                                item.style.opacity = '0';
                                item.style.transform = 'translateY(10px)';
                                setTimeout(() => {
                                    item.remove();
                                    updateTotal();
                                    Notiflix.Notify.success('Tugas berhasil dihapus');
                                }, 300);
                            }
                        } catch (err) {
                            console.error(err);
                            Notiflix.Notify.failure('Terjadi kesalahan saat menghapus.');
                        }
                    },
                    function() { // Jika tombol "Batal" diklik
                        // Tidak melakukan apa-apa
                    }
            );
        }
    </script>
</body>

</html>
