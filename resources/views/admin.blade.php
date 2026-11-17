@extends('layout.index')
@section('meta')
    <meta name="robots" content="noindex, nofollow">
@endsection
@section('main')

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Выйти из панели</button>
    </form>

    <h1>Панель администратора</h1>

    <h2>Добавление жанров:</h2>
    <form action="/admin/genres" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Название жанра" required>
        <button type="submit">Добавить жанр</button>
    </form>
    
    <h4>Список жанров:</h4>
    <ul>
        @foreach($genres as $genre)
            <li>
                {{ $genre->name }}
                <form action="/admin/genres/{{ $genre->id }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit">Удалить</button>
                </form>
            </li>
        @endforeach
    </ul>

    <h2>Добавление кинозалов</h2>
    <form action="/admin/halls" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Название зала" required>
        <input type="number" name="rows_count" placeholder="Рядов" required>
        <input type="number" name="seats_per_row" placeholder="Мест в ряду" required>
        <button type="submit">Создать зал</button>
    </form>

    <h4>Список кинозалов:</h4>
    <ul>
        @foreach($halls as $hall)
            <li>
                {{ $hall->name }} (Рядов: {{ $hall->rows_count }}, Мест в ряду: {{ $hall->seats_per_row }})
                <form action="/admin/halls/{{ $hall->id }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit">Удалить</button>
                </form>
            </li>
        @endforeach
    </ul>

    <h2>Добавление фильма</h2>
    <form action="/admin/movies" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="title" placeholder="Название фильма" required><br><br>
        <textarea name="description" placeholder="Описание фильма"></textarea><br><br>
        <input type="number" name="duration_minutes" placeholder="Длительность" required><br><br>
        <input type="number" name="release_year" placeholder="Год выпуска" required><br><br>
        <input type="text" name="age_rating" placeholder="Возрастной рейтинг" required><br><br>
        
        <label>Жанр фильма:</label><br>
        <select name="genres[]" multiple required>
            @foreach($genres as $genre)
                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
            @endforeach
        </select><br><br>

        <label>Видеофайл трейлера:</label><br>
        <input type="file" name="trailer" required><br><br>
        
        <label>Главный постер:</label><br>
        <input type="file" name="poster" required><br><br>
        
        <label>Кадры из фильма:</label><br>
        <input type="file" name="images[]" multiple><br><br>

        <button type="submit">Добавить фильм</button>
    </form>

    <h2>Добавление сеанса</h2>

    @if($errors->has('start_time'))
        <p style="color:red;">{{ $errors->first('start_time') }}</p>
    @endif
    @if($errors->any() && !$errors->has('start_time'))
        <ul style="color:red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="/admin/screenings" method="POST">
        @csrf

        <label>Фильм:</label><br>
        <select name="movie_id" required>
            @foreach($movies as $movie)
                <option value="{{ $movie->id }}">{{ $movie->title }} ({{ $movie->duration }} мин)</option>
            @endforeach
        </select><br><br>

        <label>Зал:</label><br>
        <select name="hall_id" required>
            @foreach($halls as $hall)
                <option value="{{ $hall->id }}">{{ $hall->name }}</option>
            @endforeach
        </select><br><br>

        <label>Дата и время начала:</label><br>
        <input type="datetime-local" name="start_time" required><br><br>

        <label>Формат:</label><br>
        <select name="format" required>
            <option value="2D">2D</option>
            <option value="3D">3D</option>
            <option value="IMAX">IMAX</option>
            <option value="VIP">VIP</option>
        </select><br><br>

        <label>Цена билета (₽):</label><br>
        <input type="number" name="price" min="0" step="0.01" required><br><br>

        <button type="submit">Создать сеанс</button>
    </form>

    <h4>Список сеансов:</h4>
    <table border="1" cellpadding="6" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>Фильм</th>
                <th>Зал</th>
                <th>Начало</th>
                <th>Формат</th>
                <th>Цена</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($screenings as $screening)
                <tr>
                    <td>{{ $screening->movie->title }}</td>
                    <td>{{ $screening->hall->name }}</td>
                    <td>{{ $screening->start_time->format('d.m.Y H:i') }}</td>
                    <td>{{ $screening->format }}</td>
                    <td>{{ $screening->price }} ₽</td>
                    <td>
                        <form action="/admin/screenings/{{ $screening->id }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit">Удалить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Сеансов пока нет</td></tr>
            @endforelse
        </tbody>
    </table>

    <h4>Каталог фильмов:</h4>
    <ul>
        @foreach($movies as $movie)
            <li>
                <strong>{{ $movie->title }}</strong> ({{ $movie->year }}) — {{ $movie->age_rating }}
                <form action="/admin/movies/{{ $movie->id }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit">Удалить фильм</button>
                </form>
            </li>
        @endforeach
    </ul>

@endsection
