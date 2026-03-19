<!DOCTYPE html>
<html lang="es">
@php use Illuminate\Support\Facades\Storage; @endphp
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FiftyOne — Ropa Oversize</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand:  { DEFAULT: '#3B59FF', dark: '#1A237E', light: '#e8ecff' },
            carbon: '#333333',
            ash:    '#F5F5F5',
            platinum: '#E0E0E0',
          },
          fontFamily: { sans: ['Inter', 'sans-serif'] },
        }
      }
    }
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
  <script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
  <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
  <style>
    html { scroll-behavior: smooth; }
    .hero-bg {
      background: linear-gradient(135deg, #000000 0%, #0d0d0d 40%, #0a0e2e 70%, #1a0a2e 100%);
    }
    .card-hover { transition: transform .25s ease, box-shadow .25s ease; }
    .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,.18); }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(30px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up  { animation: fadeUp .7s ease forwards; }
    .fade-up-2{ animation: fadeUp .7s .15s ease both; }
    .fade-up-3{ animation: fadeUp .7s .3s  ease both; }
  </style>
</head>
<body class="font-sans bg-white text-gray-900 antialiased">
  <div id="root"></div>

<script type="text/babel">
const { useState } = React;

const categories = [
  { id:1, name:'Hoodies',    icon:'fa-shirt',      color:'from-[#1A237E] to-[#000000]', img:'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80' },
  { id:2, name:'Camisetas',  icon:'fa-shirt',      color:'from-[#3B59FF] to-[#1A237E]', img:'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&q=80' },
  { id:3, name:'Pantalones', icon:'fa-person',     color:'from-[#333333] to-[#000000]', img:'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&q=80' },
  { id:4, name:'Accesorios', icon:'fa-hat-cowboy', color:'from-[#3B59FF] to-[#000000]', img:'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=400&q=80' },
];

const products = {!! \App\Models\Product::with('category')->latest()->take(6)->get()->map(fn($p) => [
    'id'       => $p->id,
    'name'     => $p->name,
    'price'    => (float) $p->price,
    'oldPrice' => null,
    'badge'    => $p->stock < 5 ? 'Oferta' : ($p->created_at->diffInDays() < 30 ? 'Nuevo' : null),
    'img'      => $p->image
                    ? (str_starts_with($p->image, 'http') ? $p->image : Storage::url($p->image))
                    : 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80',
    'rating'   => 5,
    'category' => $p->category?->name,
])->toJson() !!};

const testimonials = [
  { name:'Valentina R.', role:'Cliente frecuente',  text:'La calidad de las telas es increíble. Los hoodies son súper cómodos y el fit oversize queda perfecto.', avatar:'VR', stars:5 },
  { name:'Sebastián M.', role:'Streetwear lover',   text:'Llevo 3 pedidos y siempre llegan rápido y bien empacados. La camiseta boxy es mi favorita.', avatar:'SM', stars:5 },
  { name:'Camila T.',    role:'Influencer de moda', text:'FiftyOne tiene el mejor estilo oversize del mercado. Mis seguidores siempre me preguntan dónde compro.', avatar:'CT', stars:5 },
];

function Stars({ count }) {
  return (
    <div className="flex gap-0.5">
      {Array.from({length:5}).map((_,i) => (
        <i key={i} className={`fa-star text-xs ${i < count ? 'fa-solid text-[#3B59FF]' : 'fa-regular text-[#E0E0E0]'}`}></i>
      ))}
    </div>
  );
}

function Navbar({ cartCount }) {
  const [open, setOpen] = useState(false);
  const links = [
    { label: 'Inicio',     href: '#inicio' },
    { label: 'Productos',  href: '#productos' },
    { label: 'Categorías', href: '#categorias' },
    { label: 'Ofertas',    href: '#ofertas' },
    { label: 'Contacto',   href: '#contacto' },
  ];
  return (
    <nav className="fixed top-0 left-0 right-0 z-50 bg-black/95 backdrop-blur-sm border-b border-[#E0E0E0]/10">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          <a href="#" className="flex items-center gap-2">
            <div className="w-8 h-8 bg-[#3B59FF] rounded-lg flex items-center justify-center">
              <i className="fa-solid fa-shirt text-white text-sm"></i>
            </div>
            <span className="text-white font-black text-xl tracking-tight">Fifty<span className="text-[#3B59FF]">One</span></span>
          </a>
          <div className="hidden md:flex items-center gap-7">
            {links.map(l => (
              <a key={l.label} href={l.href} className="text-[#E0E0E0] hover:text-[#3B59FF] text-sm font-medium transition-colors">{l.label}</a>
            ))}
          </div>
          <div className="flex items-center gap-3">
            <button className="relative text-[#E0E0E0] hover:text-[#3B59FF] transition-colors p-2">
              <i className="fa-solid fa-cart-shopping text-lg"></i>
              {cartCount > 0 && (
                <span className="absolute -top-0.5 -right-0.5 w-4 h-4 bg-[#3B59FF] text-white text-[10px] font-bold rounded-full flex items-center justify-center">{cartCount}</span>
              )}
            </button>
            <a href="/admin/login" className="hidden sm:inline-flex items-center gap-2 bg-gradient-to-r from-[#3B59FF] to-[#7B2FBE] hover:from-[#1A237E] hover:to-[#5B1F9E] text-white text-sm font-semibold px-4 py-2 rounded-lg transition-all">
              <i className="fa-solid fa-user text-xs"></i> Ingresar
            </a>
            <button onClick={() => setOpen(!open)} className="md:hidden text-[#E0E0E0] p-2">
              <i className={`fa-solid ${open ? 'fa-xmark' : 'fa-bars'} text-lg`}></i>
            </button>
          </div>
        </div>
      </div>
      {open && (
        <div className="md:hidden bg-black border-t border-[#E0E0E0]/10 px-4 py-4 space-y-3">
          {links.map(l => (
            <a key={l.label} href={l.href} className="block text-[#E0E0E0] hover:text-[#3B59FF] text-sm font-medium py-1 transition-colors">{l.label}</a>
          ))}
          <a href="/admin/login" className="block text-center bg-gradient-to-r from-[#3B59FF] to-[#7B2FBE] text-white text-sm font-semibold px-4 py-2 rounded-lg mt-2">Ingresar</a>
        </div>
      )}
    </nav>
  );
}
</script>

<script type="text/babel">
function Hero() {
  return (
    <section id="inicio" className="hero-bg min-h-screen flex items-center relative overflow-hidden pt-16">
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-40 -right-40 w-96 h-96 bg-[#3B59FF]/10 rounded-full blur-3xl"></div>
        <div className="absolute -bottom-40 -left-40 w-96 h-96 bg-[#1A237E]/10 rounded-full blur-3xl"></div>
        <div className="absolute top-1/3 left-1/4 w-72 h-72 bg-[#7B2FBE]/15 rounded-full blur-3xl"></div>
        <div className="absolute bottom-1/4 right-1/3 w-64 h-64 bg-[#9B59B6]/10 rounded-full blur-3xl"></div>
      </div>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div className="grid lg:grid-cols-2 gap-12 items-center py-20">
          <div>
            <span className="fade-up inline-flex items-center gap-2 bg-[#3B59FF]/20 text-[#3B59FF] text-xs font-semibold px-3 py-1.5 rounded-full mb-6 border border-[#3B59FF]/30">
              <i className="fa-solid fa-fire"></i> Colección Oversize 2026
            </span>
            <h1 className="fade-up-2 text-5xl sm:text-6xl lg:text-7xl font-black text-white leading-tight mb-6">
              Viste<br />
              <span className="text-[#3B59FF]">diferente.</span><br />
              Viste grande.
            </h1>
            <p className="fade-up-3 text-[#E0E0E0] text-lg mb-8 max-w-md leading-relaxed">
              Ropa oversize con estilo streetwear. Prendas cómodas, telas premium y diseños únicos para quienes marcan tendencia.
            </p>
            <div className="fade-up-3 flex flex-wrap gap-4">
              <a href="#productos" className="inline-flex items-center gap-2 bg-gradient-to-r from-[#3B59FF] to-[#7B2FBE] hover:from-[#1A237E] hover:to-[#5B1F9E] text-white font-bold px-8 py-4 rounded-xl transition-all hover:scale-105 shadow-lg shadow-[#7B2FBE]/30">
                <i className="fa-solid fa-bag-shopping"></i> Ver colección
              </a>
              <a href="#categorias" className="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold px-8 py-4 rounded-xl border border-[#E0E0E0]/20 transition-all hover:scale-105 backdrop-blur-sm">
                <i className="fa-solid fa-grid-2"></i> Categorías
              </a>
            </div>
            <div className="mt-12 flex gap-8">
              {[['500+','Prendas'],['5K+','Clientes'],['4.9★','Valoración']].map(([n,l]) => (
                <div key={l}>
                  <p className="text-2xl font-black text-white">{n}</p>
                  <p className="text-sm text-gray-500">{l}</p>
                </div>
              ))}
            </div>
          </div>
          <div className="hidden lg:flex justify-center relative">
            <div className="relative w-[420px] h-[520px]">
              <div className="absolute inset-0 bg-gradient-to-br from-[#3B59FF]/20 to-[#1A237E]/20 rounded-3xl blur-2xl scale-110"></div>
              <img src="https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=600&q=80" alt="Buzo Oversize"
                className="relative w-full h-full object-cover rounded-3xl shadow-2xl" />
              <div className="absolute -bottom-6 -left-8 bg-white rounded-2xl p-4 shadow-2xl flex items-center gap-3">
                <div className="w-10 h-10 bg-[#3B59FF]/10 rounded-xl flex items-center justify-center">
                  <i className="fa-solid fa-fire text-[#3B59FF]"></i>
                </div>
                <div>
                  <p className="text-xs text-[#333333]">Más vendido</p>
                  <p className="text-sm font-bold text-black">Hoodie Oversize Negro</p>
                </div>
              </div>
              <div className="absolute -top-4 -right-4 bg-black text-white rounded-2xl px-4 py-3 shadow-2xl border border-[#E0E0E0]/20">
                <p className="text-xs text-[#E0E0E0]">Descuento activo</p>
                <p className="text-2xl font-black text-[#3B59FF]">25% OFF</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
</script>

<script type="text/babel">
function Categories() {
  return (
    <section id="categorias" className="py-20 bg-[#F5F5F5]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <span className="text-[#3B59FF] text-sm font-semibold uppercase tracking-widest">Explora</span>
          <h2 className="text-4xl font-black text-black mt-2">Categorías</h2>
          <p className="text-[#333333] mt-3 max-w-md mx-auto">Encuentra tu estilo oversize en cada categoría.</p>
        </div>
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-5">
          {categories.map(cat => (
            <div key={cat.id} className="card-hover group relative rounded-2xl overflow-hidden cursor-pointer shadow-md border border-[#E0E0E0]">
              <div className="aspect-[4/5] relative">
                <img src={cat.img} alt={cat.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                <div className={`absolute inset-0 bg-gradient-to-t ${cat.color} opacity-75`}></div>
                <div className="absolute inset-0 flex flex-col items-center justify-end p-5 text-white">
                  <div className="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-3 border border-white/30">
                    <i className={`fa-solid ${cat.icon} text-xl`}></i>
                  </div>
                  <h3 className="font-bold text-lg text-center leading-tight">{cat.name}</h3>
                  <button className="mt-3 bg-gradient-to-r from-[#3B59FF]/80 to-[#7B2FBE]/80 hover:from-[#3B59FF] hover:to-[#7B2FBE] text-white text-xs font-semibold px-4 py-2 rounded-lg transition-all">
                    Ver más
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

function ProductCard({ product, onAdd }) {
  const [added, setAdded] = useState(false);
  const handleAdd = () => { setAdded(true); onAdd(product); setTimeout(() => setAdded(false), 1500); };
  const fmt = (n) => 'COP $' + n.toLocaleString('es-CO', { minimumFractionDigits: 0 });
  return (
    <div className="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-[#E0E0E0] group hover:border-[#7B2FBE]/30 hover:shadow-[#7B2FBE]/10 hover:shadow-lg transition-all">
      <div className="relative aspect-square overflow-hidden bg-[#F5F5F5]">
        <img src={product.img} alt={product.name} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
        {product.badge && (
          <span className={`absolute top-3 left-3 text-xs font-bold px-2.5 py-1 rounded-full
            ${product.badge === 'Oferta' ? 'bg-red-500 text-white' :
              product.badge === 'Nuevo' ? 'bg-[#3B59FF] text-white' : 'bg-black text-white'}`}>
            {product.badge}
          </span>
        )}
        <button className="absolute top-3 right-3 w-8 h-8 bg-white rounded-full shadow flex items-center justify-center text-[#333333] hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
          <i className="fa-regular fa-heart text-sm"></i>
        </button>
      </div>
      <div className="p-4">
        <Stars count={product.rating} />
        <h3 className="font-semibold text-black mt-1.5 text-sm leading-tight">{product.name}</h3>
        <div className="flex items-center gap-2 mt-2">
          <span className="text-lg font-black text-black">{fmt(product.price)}</span>
          {product.oldPrice && <span className="text-xs text-[#333333] line-through">{fmt(product.oldPrice)}</span>}
          {product.oldPrice && <span className="text-xs font-bold text-red-500 ml-auto">-{Math.round((1-product.price/product.oldPrice)*100)}%</span>}
        </div>
        <button onClick={handleAdd}
          className={`mt-3 w-full flex items-center justify-center gap-2 text-sm font-semibold py-2.5 rounded-xl transition-all
            ${added ? 'bg-gradient-to-r from-[#3B59FF] to-[#7B2FBE] text-white' : 'bg-black hover:bg-gradient-to-r hover:from-[#1A237E] hover:to-[#5B1F9E] text-white'}`}>
          <i className={`fa-solid ${added ? 'fa-check' : 'fa-cart-plus'} text-xs`}></i>
          {added ? '¡Agregado!' : 'Agregar al carrito'}
        </button>
      </div>
    </div>
  );
}

function Products({ onAdd }) {
  return (
    <section id="productos" className="py-20 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
          <div>
            <span className="text-[#3B59FF] text-sm font-semibold uppercase tracking-widest">Catálogo</span>
            <h2 className="text-4xl font-black text-black mt-2">Prendas destacadas</h2>
          </div>
          <a href="/productos" className="inline-flex items-center gap-2 text-sm font-semibold text-[#333333] hover:text-[#3B59FF] transition-colors">
            Ver todos <i className="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
        <div className="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-5">
          {products.map(p => <ProductCard key={p.id} product={p} onAdd={onAdd} />)}
        </div>
      </div>
    </section>
  );
}
</script>

<script type="text/babel">
function PromoBanner() {
  return (
    <section id="ofertas" className="py-20 bg-black relative overflow-hidden">
      <div className="absolute inset-0 pointer-events-none">
        <div className="absolute top-0 left-1/4 w-72 h-72 bg-[#3B59FF]/10 rounded-full blur-3xl"></div>
        <div className="absolute bottom-0 right-1/4 w-72 h-72 bg-[#1A237E]/10 rounded-full blur-3xl"></div>
        <div className="absolute top-1/2 left-1/2 w-80 h-80 bg-[#7B2FBE]/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
      </div>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div className="bg-gradient-to-r from-[#3B59FF]/20 to-[#1A237E]/40 border border-[#3B59FF]/30 rounded-3xl p-10 md:p-16 flex flex-col md:flex-row items-center justify-between gap-8">
          <div>
            <span className="inline-flex items-center gap-2 bg-[#3B59FF]/20 text-[#3B59FF] text-xs font-bold px-3 py-1.5 rounded-full border border-[#3B59FF]/30 mb-4">
              <i className="fa-solid fa-bolt"></i> Oferta por tiempo limitado
            </span>
            <h2 className="text-4xl md:text-5xl font-black text-white leading-tight">
              25% OFF<br />
              <span className="text-[#3B59FF]">en toda la colección</span>
            </h2>
            <p className="text-[#E0E0E0] mt-4 max-w-md">
              Usa el código <code className="bg-white/10 text-[#3B59FF] font-mono font-bold px-2 py-0.5 rounded border border-[#E0E0E0]/20">FIFTY25</code> al finalizar tu compra. Válido hasta el 31 de marzo.
            </p>
          </div>
          <div className="flex flex-col items-center gap-4 flex-shrink-0">
            <div className="flex gap-3">
              {[['08','Hrs'],['45','Min'],['30','Seg']].map(([n,l]) => (
                <div key={l} className="bg-white/5 backdrop-blur-sm border border-[#E0E0E0]/10 rounded-2xl w-20 h-20 flex flex-col items-center justify-center">
                  <span className="text-3xl font-black text-white">{n}</span>
                  <span className="text-[#E0E0E0] text-xs">{l}</span>
                </div>
              ))}
            </div>
            <a href="#productos" className="inline-flex items-center gap-2 bg-gradient-to-r from-[#3B59FF] to-[#7B2FBE] hover:from-[#1A237E] hover:to-[#5B1F9E] text-white font-bold px-8 py-4 rounded-xl transition-all hover:scale-105 shadow-lg shadow-[#7B2FBE]/30 w-full justify-center">
              <i className="fa-solid fa-bag-shopping"></i> Aprovechar oferta
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}

function Testimonials() {
  return (
    <section className="py-20 bg-[#F5F5F5]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <span className="text-[#3B59FF] text-sm font-semibold uppercase tracking-widest">Opiniones</span>
          <h2 className="text-4xl font-black text-black mt-2">Lo que dicen nuestros clientes</h2>
        </div>
        <div className="grid md:grid-cols-3 gap-6">
          {testimonials.map((t,i) => (
            <div key={i} className="card-hover bg-white rounded-2xl p-6 shadow-sm border border-[#E0E0E0]">
              <Stars count={t.stars} />
              <p className="text-[#333333] mt-4 leading-relaxed text-sm">"{t.text}"</p>
              <div className="flex items-center gap-3 mt-6">
                <div className="w-10 h-10 bg-gradient-to-br from-[#3B59FF] to-[#1A237E] rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                  {t.avatar}
                </div>
                <div>
                  <p className="font-semibold text-black text-sm">{t.name}</p>
                  <p className="text-[#333333] text-xs">{t.role}</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

function Footer() {
  return (
    <footer id="contacto" className="bg-black text-[#333333] pt-16 pb-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-2 md:grid-cols-4 gap-10 mb-12">
          <div className="col-span-2 md:col-span-1">
            <div className="flex items-center gap-2 mb-4">
              <div className="w-8 h-8 bg-[#3B59FF] rounded-lg flex items-center justify-center">
                <i className="fa-solid fa-shirt text-white text-sm"></i>
              </div>
              <span className="text-white font-black text-xl">Fifty<span className="text-[#3B59FF]">One</span></span>
            </div>
            <p className="text-sm leading-relaxed mb-5 text-[#E0E0E0]">Tu tienda de ropa oversize. Estilo streetwear, telas premium y envíos a todo el país.</p>
            <div className="flex gap-3">
              {[['fa-instagram','hover:text-pink-500'],['fa-facebook','hover:text-[#3B59FF]'],['fa-tiktok','hover:text-white'],['fa-youtube','hover:text-red-500']].map(([icon,hover]) => (
                <a key={icon} href="#" className={`w-9 h-9 bg-white/5 hover:bg-white/10 border border-[#E0E0E0]/10 rounded-lg flex items-center justify-center transition-colors text-[#E0E0E0] ${hover}`}>
                  <i className={`fa-brands ${icon} text-sm`}></i>
                </a>
              ))}
            </div>
          </div>
          {[
            { title:'Tienda',  links:['Hoodies','Camisetas','Pantalones','Accesorios'] },
            { title:'Empresa', links:['Sobre nosotros','Blog','Trabaja con nosotros','Prensa'] },
            { title:'Soporte', links:['Contacto','Envíos','Devoluciones','FAQ'] },
          ].map(col => (
            <div key={col.title}>
              <h4 className="text-white font-semibold text-sm mb-4">{col.title}</h4>
              <ul className="space-y-2.5">
                {col.links.map(l => (
                  <li key={l}><a href="#" className="text-sm text-[#E0E0E0] hover:text-[#3B59FF] transition-colors">{l}</a></li>
                ))}
              </ul>
            </div>
          ))}
        </div>
        <div className="border-t border-[#E0E0E0]/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p className="text-xs text-[#333333]">© 2026 FiftyOne. Todos los derechos reservados.</p>
          <div className="flex items-center gap-4 text-xs text-[#E0E0E0]">
            <a href="#" className="hover:text-[#3B59FF] transition-colors">Privacidad</a>
            <a href="#" className="hover:text-[#3B59FF] transition-colors">Términos</a>
            <a href="/admin/login" className="hover:text-[#3B59FF] transition-colors">Admin</a>
          </div>
        </div>
      </div>
    </footer>
  );
}

function App() {
  const [cartCount, setCartCount] = useState(0);
  const handleAdd = () => setCartCount(c => c + 1);
  return (
    <>
      <Navbar cartCount={cartCount} />
      <Hero />
      <Categories />
      <Products onAdd={handleAdd} />
      <PromoBanner />
      <Testimonials />
      <Footer />
    </>
  );
}

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(<App />);
</script>

</body>
</html>
