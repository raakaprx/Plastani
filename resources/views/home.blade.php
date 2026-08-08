<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plastani</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 2rem; background: #f6f8fb; color: #1f2937; }
        .card { background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 1rem; }
        h1 { color: #047857; }
        .muted { color: #6b7280; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Selamat datang di Plastani</h1>
        <p class="muted">Platform ini membantu UMKM dan petani menyalurkan hasil produksi secara online.</p>
    </div>

    <div class="card">
        <h2>Produk terbaru</h2>
        @if($products->isNotEmpty())
            <ul>
                @foreach($products as $product)
                    <li>{{ $product->name }}</li>
                @endforeach
            </ul>
        @else
            <p class="muted">Belum ada data produk yang tersedia. Anda bisa menambahkan data melalui database atau admin panel.</p>
        @endif
    </div>
</body>
</html>
