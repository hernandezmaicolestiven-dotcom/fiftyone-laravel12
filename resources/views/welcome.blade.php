<!DOCTYPE html>
<html lang="es">
@php use Illuminate\Support\Facades\Storage; @endphp
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FiftyOne — Ropa Oversize</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#3B59FF">
  <link rel="manifest" href="/manifest.json">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
  <script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
  <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
  <style>
    html { scroll-behavior: smooth; }
    body { font-family: 'Inter', sans-serif; }
    .hero-bg { background: linear-gradient(135deg,#000 0%,#0d0d0d 40%,#0a0e2e 70%,#1a0a2e 100%); }
    .card-hover { transition: transform .25s ease, box-shadow .25s ease; }
    .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,.18); }
    @keyframes fadeUp { from{opacity:0;transform:translateY(30px)} to{opacity:1;transform:translateY(0)} }
    .fade-up   { animation: fadeUp .7s ease forwards; }
    .fade-up-2 { animation: fadeUp .7s .15s ease both; }
    .fade-up-3 { animation: fadeUp .7s .3s  ease both; }
    @keyframes slideInRight { from{transform:translateX(110%)} to{transform:translateX(0)} }
    @keyframes slideOutRight{ from{transform:translateX(0)} to{transform:translateX(110%)} }
    @keyframes fadeIn  { from{opacity:0} to{opacity:1} }
    @keyframes fadeOut { from{opacity:1} to{opacity:0} }
    @keyframes slideUp { from{opacity:0;transform:translateY(28px) scale(.97)} to{opacity:1;transform:translateY(0) scale(1)} }
    @keyframes bounceIn{ 0%{transform:scale(.5);opacity:0} 60%{transform:scale(1.1)} 80%{transform:scale(.95)} 100%{transform:scale(1);opacity:1} }
    @keyframes pulse   { 0%,100%{transform:scale(1)} 50%{transform:scale(1.08)} }
    @keyframes shimmer { 0%{background-position:-200% 0} 100%{background-position:200% 0} }
    .drawer-in  { animation: slideInRight .4s cubic-bezier(.22,1,.36,1) forwards; }
    .drawer-out { animation: slideOutRight .35s cubic-bezier(.4,0,.2,1) forwards; }
    .modal-enter{ animation: slideUp .3s cubic-bezier(.22,1,.36,1); }
    .bounce-in  { animation: bounceIn .5s cubic-bezier(.22,1,.36,1); }
    .qty-btn { width:32px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:700;cursor:pointer;transition:.2s;border:1.5px solid #e5e7eb;background:white; }
    .qty-btn:hover { border-color:#3B59FF;color:#3B59FF;background:#eef1ff; }
    .cart-item-row { transition: all .2s ease; }
    .cart-item-row:hover { background:#f8f9ff; }
    .glass { background: rgba(255,255,255,0.08); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    .shimmer-line { background: linear-gradient(90deg,#f0f0f0 25%,#e0e0e0 50%,#f0f0f0 75%); background-size:200% 100%; animation: shimmer 1.5s infinite; border-radius:8px; }
  </style>
  <script>
  window.__AUTH__ = {!! json_encode($authData) !!};
  window.__WISHLIST__ = {!! json_encode($wishlistIds ?? []) !!};
  window.__STATS__ = {!! json_encode(['customers' => \App\Models\User::where('role','customer')->count(), 'products' => \App\Models\Product::count(), 'orders' => \App\Models\Order::count()]) !!};
  </script>
</head>
<body class="bg-white text-gray-900 antialiased">
<div id="root"></div>

@php
$productosJS = $products->map(fn($p) => [
  'id'          => $p->id,
  'name'        => $p->name,
  'price'       => (float) $p->price,
  'badge'       => $p->stock < 5 ? 'Oferta' : ($p->created_at->diffInDays() < 30 ? 'Nuevo' : null),
  'img'         => $p->image ? (str_starts_with($p->image,'http') ? $p->image : Storage::url($p->image)) : 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80',
  'rating'      => $p->reviews->count() ? round($p->reviews->avg('rating'),1) : 5,
  'reviews'     => $p->reviews->count(),
  'sizes'       => $p->sizes ?? ['S','M','L','XL'],
  'colors'      => $p->colors ?? [],
  'stock'       => $p->stock,
  'category_id' => $p->category_id,
  'wishlisted'  => in_array($p->id, $wishlistIds ?? []),
]);
@endphp
<script type="text/plain" id="products-data">{!! json_encode($productosJS) !!}</script>
<script type="text/plain" id="reviews-data">{!! json_encode($reviews) !!}</script>
@verbatim
<script type="text/babel">
const { useState, useEffect } = React;

window.__PRODUCTS__ = JSON.parse(document.getElementById('products-data').textContent);

const dbProducts   = window.__PRODUCTS__;
const gradientBg   = 'linear-gradient(90deg,#3B59FF,#7B2FBE)';

const categories = [
  { id:1, name:'Hoodies',    icon:'fa-shirt',      color:'from-[#1A237E] to-[#000000]', img:'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=400&q=80' },
  { id:2, name:'Camisetas',  icon:'fa-shirt',      color:'from-[#3B59FF] to-[#1A237E]', img:'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&q=80' },
  { id:3, name:'Pantalones', icon:'fa-person',     color:'from-[#333333] to-[#000000]', img:'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&q=80' },
  { id:4, name:'Accesorios', icon:'fa-hat-cowboy', color:'from-[#3B59FF] to-[#000000]', img:'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=400&q=80' },
];
const testimonials = JSON.parse(document.getElementById('reviews-data').textContent);
window.__AUTH__ = window.__AUTH__ || {};

const fmt = n => '$ ' + Math.round(parseFloat(n)).toLocaleString('es-CO');

function Stars({ count }) {
  return (
    <div className="flex gap-0.5">
      {Array.from({length:5}).map((_,i) => (
        <i key={i} className={`fa-star text-xs ${i < count ? 'fa-solid text-[#3B59FF]' : 'fa-regular text-[#E0E0E0]'}`}></i>
      ))}
    </div>
  );
}

// ── Cart Drawer ─────────────────────────────────────────────────────────────
function CartDrawer({ open, onClose, cart, onUpdateQty, onRemove, onClear, onCheckout }) {
  const auth = window.__AUTH__ || {};
  const total = cart.reduce((s,i) => s + i.price * i.qty, 0);
  const count = cart.reduce((s,i) => s + i.qty, 0);

  return (
    <>
      {/* Overlay */}
      <div onClick={onClose}
        className="fixed inset-0 z-40 transition-all duration-300"
        style={{
          background:'rgba(0,0,0,0.55)',
          backdropFilter:'blur(6px)',
          WebkitBackdropFilter:'blur(6px)',
          opacity: open ? 1 : 0,
          pointerEvents: open ? 'auto' : 'none',
          transition:'opacity .35s ease',
        }} />

      {/* Drawer panel */}
      <div className="fixed top-0 right-0 h-full z-50 flex flex-col"
        style={{
          width:'min(420px,100vw)',
          background:'linear-gradient(160deg,#0f0f1a 0%,#13132b 60%,#0d0d1f 100%)',
          boxShadow:'-8px 0 60px rgba(59,89,255,.25)',
          transform: open ? 'translateX(0)' : 'translateX(105%)',
          transition:'transform .4s cubic-bezier(.22,1,.36,1)',
        }}>

        {/* Top accent line */}
        <div className="h-0.5 w-full" style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE,#3B59FF)',backgroundSize:'200% 100%',animation:'shimmer 2s infinite'}}></div>

        {/* Header */}
        <div className="flex items-center justify-between px-6 py-5">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-2xl flex items-center justify-center relative overflow-hidden"
              style={{background:'linear-gradient(135deg,#3B59FF,#7B2FBE)'}}>
              <i className="fa-solid fa-bag-shopping text-white text-sm"></i>
              {count > 0 && (
                <span className="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-white text-[9px] font-black flex items-center justify-center">{count}</span>
              )}
            </div>
            <div>
              <h2 className="font-black text-white text-lg leading-none">Mi carrito</h2>
              <p className="text-xs mt-0.5" style={{color:'rgba(255,255,255,.4)'}}>
                {count === 0 ? 'Vacío' : `${count} producto${count > 1 ? 's' : ''}`}
              </p>
            </div>
          </div>
          <button onClick={onClose}
            className="w-9 h-9 rounded-xl flex items-center justify-center transition-all"
            style={{background:'rgba(255,255,255,.07)',border:'1px solid rgba(255,255,255,.1)'}}>
            <i className="fa-solid fa-xmark text-white text-sm"></i>
          </button>
        </div>

        {/* Divider */}
        <div className="mx-6 h-px" style={{background:'rgba(255,255,255,.07)'}}></div>

        {/* Items */}
        <div className="flex-1 overflow-y-auto px-6 py-4 space-y-3"
          style={{scrollbarWidth:'thin',scrollbarColor:'rgba(255,255,255,.1) transparent'}}>
          {cart.length === 0 ? (
            <div className="flex flex-col items-center justify-center h-full py-20 text-center">
              <div className="w-24 h-24 rounded-3xl flex items-center justify-center mb-5"
                style={{background:'rgba(59,89,255,.1)',border:'1px solid rgba(59,89,255,.2)'}}>
                <i className="fa-solid fa-bag-shopping text-4xl" style={{color:'rgba(59,89,255,.5)'}}></i>
              </div>
              <p className="font-bold text-white text-lg">Carrito vacío</p>
              <p className="text-sm mt-1" style={{color:'rgba(255,255,255,.35)'}}>Agrega productos para continuar</p>
              <button onClick={onClose}
                className="mt-6 px-6 py-2.5 rounded-xl text-white text-sm font-semibold transition-all hover:scale-105"
                style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE)'}}>
                Explorar productos
              </button>
            </div>
          ) : cart.map((item, idx) => (
            <div key={item.id} className="cart-item-row flex items-center gap-3 rounded-2xl p-3 group"
              style={{background:'rgba(255,255,255,.05)',border:'1px solid rgba(255,255,255,.07)',transition:'all .2s'}}>
              {/* Image */}
              <div className="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0 relative"
                style={{background:'rgba(255,255,255,.08)'}}>
                {item.img
                  ? <img src={item.img} className="w-full h-full object-cover" alt={item.name} />
                  : <div className="w-full h-full flex items-center justify-center"><i className="fa-solid fa-shirt text-white opacity-30 text-xl"></i></div>
                }
              </div>
              {/* Info */}
              <div className="flex-1 min-w-0">
                <p className="font-semibold text-white text-sm truncate">{item.name}</p>
                <p className="text-xs mt-0.5" style={{color:'rgba(255,255,255,.4)'}}>{fmt(item.price)} c/u</p>
                <p className="font-black text-sm mt-1" style={{background:'linear-gradient(90deg,#3B59FF,#a78bfa)',WebkitBackgroundClip:'text',WebkitTextFillColor:'transparent'}}>
                  {fmt(item.price * item.qty)}
                </p>
              </div>
              {/* Qty + remove */}
              <div className="flex flex-col items-end gap-2">
                <div className="flex items-center gap-1.5 rounded-xl p-1" style={{background:'rgba(255,255,255,.07)'}}>
                  <button className="qty-btn" style={{background:'transparent',border:'none',color:'white'}}
                    onClick={() => onUpdateQty(item.id, -1)}>
                    <i className="fa-solid fa-minus text-xs"></i>
                  </button>
                  <span className="w-6 text-center font-black text-sm text-white">{item.qty}</span>
                  <button className="qty-btn" style={{background:'transparent',border:'none',color:'white'}}
                    onClick={() => onUpdateQty(item.id, 1)}>
                    <i className="fa-solid fa-plus text-xs"></i>
                  </button>
                </div>
                <button onClick={() => onRemove(item.id)}
                  className="text-xs px-2 py-1 rounded-lg transition-all opacity-0 group-hover:opacity-100"
                  style={{color:'rgba(239,68,68,.7)',background:'rgba(239,68,68,.08)'}}>
                  <i className="fa-solid fa-trash-can"></i>
                </button>
              </div>
            </div>
          ))}
        </div>

        {/* Footer */}
        {cart.length > 0 && (
          <div className="px-6 pb-6 pt-4 space-y-4">
            <div className="h-px" style={{background:'rgba(255,255,255,.07)'}}></div>

            {/* Total row */}
            <div className="flex justify-between items-center">
              <span className="text-sm font-medium" style={{color:'rgba(255,255,255,.5)'}}>Total del pedido</span>
              <span className="font-black text-2xl text-white">{fmt(total)}</span>
            </div>

            {/* Checkout btn */}
            <button onClick={() => { if(!auth.loggedIn){ window.location.href='/login?redirect=checkout'; return; } onCheckout(); }}
              className="w-full py-4 rounded-2xl text-white font-black text-sm flex items-center justify-center gap-2 transition-all hover:scale-[1.02] active:scale-[.98]"
              style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE)',boxShadow:'0 8px 30px rgba(59,89,255,.4)'}}>
              <i className="fa-solid fa-lock text-xs"></i>
              {auth.loggedIn ? `Finalizar pedido · ${fmt(total)}` : 'Inicia sesión para pagar'}
            </button>

            {/* Clear */}
            <button onClick={onClear}
              className="w-full py-2 rounded-xl text-xs font-medium transition-all"
              style={{color:'rgba(255,255,255,.3)'}}>
              <i className="fa-solid fa-trash-can mr-1"></i> Vaciar carrito
            </button>
          </div>
        )}
      </div>
    </>
  );
}

// ── Payment methods config ──────────────────────────────────────────────────
const PAYMENT_METHODS = [
  { id:'nequi',       label:'Nequi',       sub:'Instantáneo',    icon:'fa-mobile-screen-button',  color:'#7B2FBE', bg:'rgba(123,47,190,.15)', border:'rgba(123,47,190,.4)'  },
  { id:'daviplata',   label:'Daviplata',   sub:'Billetera',      icon:'fa-wallet',                color:'#E8001C', bg:'rgba(232,0,28,.12)',   border:'rgba(232,0,28,.35)'   },
  { id:'pse',         label:'PSE',         sub:'Débito banco',   icon:'fa-building-columns',      color:'#00A859', bg:'rgba(0,168,89,.12)',   border:'rgba(0,168,89,.35)'   },
  { id:'bancolombia', label:'Bancolombia', sub:'Transferencia',  icon:'fa-arrow-right-arrow-left', color:'#FDDA24', bg:'rgba(253,218,36,.12)', border:'rgba(253,218,36,.35)' },
  { id:'efecty',      label:'Efecty',      sub:'Efectivo',       icon:'fa-money-bill-wave',       color:'#FFB800', bg:'rgba(255,184,0,.12)',  border:'rgba(255,184,0,.35)'  },
  { id:'tarjeta',     label:'Tarjeta',     sub:'Créd / Déb',     icon:'fa-credit-card',           color:'#3B59FF', bg:'rgba(59,89,255,.15)', border:'rgba(59,89,255,.4)'   },
];

// ── Checkout Modal — 3 pasos ────────────────────────────────────────────────
function CheckoutModal({ cart, onClose, onSuccess }) {
  const [step,      setStep]      = useState(1);
  const [name,      setName]      = useState('');
  const [email,     setEmail]     = useState('');
  const [phone,     setPhone]     = useState('');
  const [notes,    setNotes]   = useState('');
  const [address,  setAddress] = useState('');
  const [city,     setCity]    = useState('');
  const [payMethod, setPayMethod] = useState(null);
  const [pseBank,   setPseBank]   = useState('');
  const [cardNum,   setCardNum]   = useState('');
  const [cardExp,   setCardExp]   = useState('');
  const [cardCvv,   setCardCvv]   = useState('');
  const [cardName,  setCardName]  = useState('');
  const [err,       setErr]       = useState('');
  const [loading,   setLoading]   = useState(false);
  const [coupon,    setCoupon]    = useState('');
  const [discount,  setDiscount]  = useState(0);
  const [couponMsg, setCouponMsg] = useState('');
  const rawTotal = cart.reduce((s,i) => s + i.price * i.qty, 0);
  const total = Math.max(0, rawTotal - discount);

  const applyCoupon = async () => {
    if (!coupon.trim()) return;
    try {
      const res = await fetch('/coupon/validate?code=' + encodeURIComponent(coupon.trim()) + '&total=' + rawTotal, {
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content}
      });
      const data = await res.json();
      if (data.valid) {
        setDiscount(data.discount);
        setCouponMsg('✅ Cupón aplicado: -' + fmt(data.discount));
      } else {
        setDiscount(0);
        setCouponMsg('❌ ' + (data.message || 'Cupón inválido'));
      }
    } catch { setCouponMsg('❌ Error al validar el cupón'); }
  };

  const goStep2 = () => {
    if (!auth.loggedIn) { window.location.href = '/login'; return; }
    setErr(''); setStep(2);
  };
  const goStep3 = () => {
    if (!name.trim()) { setErr('El nombre es requerido'); return; }
    if (!address.trim()) { setErr('La dirección de envío es requerida'); return; }
    if (!city.trim()) { setErr('La ciudad es requerida'); return; }
    setErr(''); setStep(3);
  };

  const submit = async () => {
    if (!payMethod) { setErr('Selecciona un método de pago'); return; }
    if (payMethod === 'tarjeta' && (!cardNum || !cardExp || !cardCvv || !cardName)) { setErr('Completa los datos de la tarjeta'); return; }
    setErr(''); setLoading(true);
    try {
      const res = await fetch('/orders', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
        body: JSON.stringify({
          customer_name: name.trim(), customer_email: email||null, customer_phone: phone||null,
          shipping_address: address.trim()||null, city: city.trim()||null,
          notes: (notes ? notes+' | ' : '')+'Pago: '+payMethod+(coupon?' | Cupón: '+coupon:''),
          items: cart.map(i => ({product_id:i.id, quantity:i.qty})),
          coupon_code: coupon || null,
        }),
      });
      const data = await res.json();
      if (res.ok && data.success) onSuccess(data.order_id);
      else setErr('Error al procesar el pedido.');
    } catch { setErr('Error de conexión.'); }
    finally { setLoading(false); }
  };

  const iCls = "w-full rounded-xl px-4 py-3 text-sm text-white font-medium focus:outline-none transition-all";
  const iSty = {background:'rgba(255,255,255,.07)',border:'1.5px solid rgba(255,255,255,.1)'};
  const fmtCard = v => v.replace(/\D/g,'').slice(0,16).replace(/(.{4})/g,'$1 ').trim();
  const fmtExp  = v => { const d=v.replace(/\D/g,'').slice(0,4); return d.length>2?d.slice(0,2)+'/'+d.slice(2):d; };
  const stepLabels = ['Resumen','Datos','Pago'];

  return (
    <div className="fixed inset-0 flex items-end sm:items-center justify-center sm:p-4" style={{zIndex:60}}>
      <div className="absolute inset-0" onClick={onClose}
        style={{background:'rgba(0,0,0,.75)',backdropFilter:'blur(10px)',WebkitBackdropFilter:'blur(10px)'}} />

      <div className="modal-enter relative w-full sm:max-w-lg overflow-hidden flex flex-col"
        style={{background:'linear-gradient(160deg,#0c0c1e,#111128)',border:'1px solid rgba(255,255,255,.08)',borderRadius:'28px 28px 0 0',boxShadow:'0 -24px 80px rgba(59,89,255,.35)',maxHeight:'92dvh'}}>

        {/* Shimmer bar */}
        <div className="h-1" style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE,#06b6d4,#3B59FF)',backgroundSize:'300% 100%',animation:'shimmer 3s infinite'}}></div>
        <div className="flex justify-center pt-3 pb-0 sm:hidden"><div className="w-10 h-1 rounded-full" style={{background:'rgba(255,255,255,.15)'}}></div></div>

        {/* Header with step pills */}
        <div className="flex items-center justify-between px-6 pt-4 pb-3">
          <div className="flex items-center gap-1.5">
            {stepLabels.map((lbl,i) => {
              const s=i+1, done=s<step, cur=s===step;
              return (
                <div key={s} className="flex items-center gap-1.5">
                  <div className="flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold transition-all duration-300"
                    style={{background:done?'rgba(16,185,129,.2)':cur?'linear-gradient(90deg,#3B59FF,#7B2FBE)':'rgba(255,255,255,.06)',
                            color:done?'#6ee7b7':cur?'white':'rgba(255,255,255,.3)',
                            border:done?'1px solid rgba(16,185,129,.3)':cur?'none':'1px solid rgba(255,255,255,.07)'}}>
                    {done?<i className="fa-solid fa-check" style={{fontSize:'9px'}}></i>:<span>{s}</span>}
                    <span>{lbl}</span>
                  </div>
                  {s<3&&<div className="w-4 h-px" style={{background:'rgba(255,255,255,.1)'}}></div>}
                </div>
              );
            })}
          </div>
          <button onClick={onClose} className="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
            style={{background:'rgba(255,255,255,.06)',border:'1px solid rgba(255,255,255,.08)'}}>
            <i className="fa-solid fa-xmark text-white text-sm"></i>
          </button>
        </div>
        <div className="h-px mx-6" style={{background:'rgba(255,255,255,.06)'}}></div>

        {/* ── Step 1: Resumen ── */}
        {step===1 && (
          <div className="px-6 py-5">
            <div className="space-y-2 max-h-48 overflow-y-auto pr-1 mb-4" style={{scrollbarWidth:'thin',scrollbarColor:'rgba(255,255,255,.08) transparent'}}>
              {cart.map(i => (
                <div key={i.id} className="flex items-center gap-3 rounded-2xl p-3"
                  style={{background:'rgba(255,255,255,.04)',border:'1px solid rgba(255,255,255,.06)'}}>
                  <div className="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0" style={{background:'rgba(255,255,255,.07)'}}>
                    {i.img&&<img src={i.img} className="w-full h-full object-cover" alt={i.name}/>}
                  </div>
                  <div className="flex-1 min-w-0">
                    <p className="text-white text-sm font-semibold truncate">{i.name}</p>
                    <p className="text-xs mt-0.5" style={{color:'rgba(255,255,255,.35)'}}>x{i.qty} · {fmt(i.price)} c/u</p>
                  </div>
                  <p className="font-black text-sm flex-shrink-0"
                    style={{background:'linear-gradient(90deg,#60a5fa,#a78bfa)',WebkitBackgroundClip:'text',WebkitTextFillColor:'transparent'}}>
                    {fmt(i.price*i.qty)}
                  </p>
                </div>
              ))}
            </div>
            <div className="flex justify-between items-center rounded-2xl px-5 py-3.5 mb-3"
              style={{background:'linear-gradient(90deg,rgba(59,89,255,.15),rgba(123,47,190,.15))',border:'1px solid rgba(59,89,255,.2)'}}>
              <span className="text-sm font-medium" style={{color:'rgba(255,255,255,.55)'}}>Total a pagar</span>
              <div className="text-right">
                {discount > 0 && <p className="text-xs line-through" style={{color:'rgba(255,255,255,.3)'}}>{fmt(rawTotal)}</p>}
                <span className="font-black text-2xl text-white">{fmt(total)}</span>
              </div>
            </div>
            {/* Campo cupón */}
            <div className="flex gap-2 mb-4">
              <input value={coupon} onChange={e=>setCoupon(e.target.value.toUpperCase())}
                     placeholder="Código de cupón"
                     className="flex-1 rounded-xl px-4 py-2.5 text-sm text-white font-medium focus:outline-none"
                     style={{background:'rgba(255,255,255,.07)',border:'1.5px solid rgba(255,255,255,.1)'}}
                     onKeyDown={e=>e.key==='Enter'&&applyCoupon()} />
              <button onClick={applyCoupon}
                      className="px-4 py-2.5 rounded-xl text-xs font-bold text-white transition hover:opacity-80"
                      style={{background:'rgba(59,89,255,.4)',border:'1px solid rgba(59,89,255,.5)'}}>
                Aplicar
              </button>
            </div>
            {couponMsg && <p className="text-xs mb-3" style={{color: couponMsg.startsWith('✅') ? '#6ee7b7' : '#fca5a5'}}>{couponMsg}</p>}
            <button onClick={goStep2} className="w-full py-4 rounded-2xl text-white font-black text-sm flex items-center justify-center gap-2 transition-all hover:scale-[1.02]"
              style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE)',boxShadow:'0 10px 35px rgba(59,89,255,.45)'}}>
              Continuar <i className="fa-solid fa-arrow-right text-xs"></i>
            </button>
          </div>
        )}

        {/* ── Step 2: Datos ── */}
        {step===2 && (
          <div className="px-6 py-5 space-y-3">
            {err&&<div className="flex items-center gap-2 rounded-xl px-4 py-3 text-sm"
              style={{background:'rgba(239,68,68,.1)',border:'1px solid rgba(239,68,68,.25)',color:'#fca5a5'}}>
              <i className="fa-solid fa-circle-exclamation"></i> {err}</div>}
            <div>
              <label className="block text-xs font-bold mb-2 uppercase tracking-widest" style={{color:'rgba(255,255,255,.35)'}}>Nombre completo *</label>
              <input value={name} onChange={e=>setName(e.target.value)} placeholder="Tu nombre completo" className={iCls} style={iSty}/>
            </div>
            <div className="grid grid-cols-2 gap-3">
              <div>
                <label className="block text-xs font-bold mb-2 uppercase tracking-widest" style={{color:'rgba(255,255,255,.35)'}}>Email</label>
                <input type="email" value={email} onChange={e=>setEmail(e.target.value)} placeholder="tu@email.com" className={iCls} style={iSty}/>
              </div>
              <div>
                <label className="block text-xs font-bold mb-2 uppercase tracking-widest" style={{color:'rgba(255,255,255,.35)'}}>Teléfono</label>
                <input type="tel" value={phone} onChange={e=>setPhone(e.target.value)} placeholder="300 000 0000" className={iCls} style={iSty}/>
              </div>
            </div>
            <div>
              <label className="block text-xs font-bold mb-2 uppercase tracking-widest" style={{color:'rgba(255,255,255,.35)'}}>Dirección de envío *</label>
              <input value={address} onChange={e=>setAddress(e.target.value)} placeholder="Calle, carrera, barrio, número..." className={iCls} style={iSty}/>
            </div>
            <div>
              <label className="block text-xs font-bold mb-2 uppercase tracking-widest" style={{color:'rgba(255,255,255,.35)'}}>Ciudad *</label>
              <input value={city} onChange={e=>setCity(e.target.value)} placeholder="Ej: Medellín, Bogotá, Cali..." className={iCls} style={iSty}/>
            </div>
            <div>
              <label className="block text-xs font-bold mb-2 uppercase tracking-widest" style={{color:'rgba(255,255,255,.35)'}}>Notas adicionales</label>
              <textarea value={notes} onChange={e=>setNotes(e.target.value)} rows={2} placeholder="Instrucciones especiales, apartamento, etc..." className={iCls+' resize-none'} style={iSty}/>
            </div>
            <div className="flex gap-3 pt-1">
              <button onClick={()=>setStep(1)} className="flex-1 py-3.5 rounded-2xl text-sm font-bold"
                style={{background:'rgba(255,255,255,.06)',border:'1px solid rgba(255,255,255,.08)',color:'rgba(255,255,255,.5)'}}>
                <i className="fa-solid fa-arrow-left mr-1 text-xs"></i> Volver
              </button>
              <button onClick={goStep3} className="flex-[2] py-3.5 rounded-2xl text-white font-black text-sm flex items-center justify-center gap-2 transition-all hover:scale-[1.02]"
                style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE)',boxShadow:'0 8px 30px rgba(59,89,255,.4)'}}>
                Método de pago <i className="fa-solid fa-arrow-right text-xs"></i>
              </button>
            </div>
          </div>
        )}

        {/* ── Step 3: Pago ── */}
        {step===3 && (
          <div className="flex flex-col" style={{maxHeight:'60vh'}}>
            {/* Scrollable content */}
            <div className="flex-1 overflow-y-auto px-6 py-4 space-y-4" style={{scrollbarWidth:'thin',scrollbarColor:'rgba(255,255,255,.1) transparent'}}>
              {err&&<div className="flex items-center gap-2 rounded-xl px-4 py-3 text-sm"
                style={{background:'rgba(239,68,68,.1)',border:'1px solid rgba(239,68,68,.25)',color:'#fca5a5'}}>
                <i className="fa-solid fa-circle-exclamation"></i> {err}</div>}

              <p className="text-xs font-bold uppercase tracking-widest" style={{color:'rgba(255,255,255,.35)'}}>Elige cómo pagar</p>

              {/* Payment grid */}
              <div className="grid grid-cols-3 gap-2">
                {PAYMENT_METHODS.map(m => (
                  <button key={m.id} onClick={()=>setPayMethod(m.id)}
                    className="flex flex-col items-center gap-2 rounded-2xl p-3 transition-all hover:scale-[1.04] active:scale-[.97]"
                    style={{
                      background: payMethod===m.id ? m.bg : 'rgba(255,255,255,.04)',
                      border: payMethod===m.id ? `1.5px solid ${m.border}` : '1.5px solid rgba(255,255,255,.07)',
                      boxShadow: payMethod===m.id ? `0 4px 20px ${m.bg}` : 'none',
                    }}>
                    <div className="w-9 h-9 rounded-xl flex items-center justify-center"
                      style={{background: payMethod===m.id ? m.bg : 'rgba(255,255,255,.08)'}}>
                      <i className={`fa-solid ${m.icon} text-base`} style={{color: m.color}}></i>
                    </div>
                    <span className="text-xs font-bold text-white leading-tight text-center">{m.label}</span>
                    <span className="text-[10px] leading-tight text-center" style={{color:'rgba(255,255,255,.35)'}}>{m.sub}</span>
                    {payMethod===m.id&&(
                      <div className="w-4 h-4 rounded-full flex items-center justify-center" style={{background:'linear-gradient(135deg,#3B59FF,#7B2FBE)'}}>
                        <i className="fa-solid fa-check text-white" style={{fontSize:'8px'}}></i>
                      </div>
                    )}
                  </button>
                ))}
              </div>

              {/* Tarjeta fields */}
              {payMethod==='tarjeta'&&(
                <div className="space-y-3 rounded-2xl p-4" style={{background:'rgba(59,89,255,.08)',border:'1px solid rgba(59,89,255,.2)'}}>
                  <p className="text-xs font-bold uppercase tracking-widest" style={{color:'rgba(255,255,255,.4)'}}>Datos de la tarjeta</p>
                  <input value={cardNum} onChange={e=>setCardNum(fmtCard(e.target.value))} placeholder="0000 0000 0000 0000" maxLength={19} className={iCls} style={iSty}/>
                  <input value={cardName} onChange={e=>setCardName(e.target.value)} placeholder="Nombre en la tarjeta" className={iCls} style={iSty}/>
                  <div className="grid grid-cols-2 gap-3">
                    <input value={cardExp} onChange={e=>setCardExp(fmtExp(e.target.value))} placeholder="MM/AA" maxLength={5} className={iCls} style={iSty}/>
                    <input value={cardCvv} onChange={e=>setCardCvv(e.target.value.replace(/\D/g,'').slice(0,4))} placeholder="CVV" maxLength={4} className={iCls} style={iSty}/>
                  </div>
                </div>
              )}

              {/* PSE */}
              {payMethod==='pse'&&(
                <div className="rounded-2xl p-4 space-y-3" style={{background:'rgba(0,168,89,.08)',border:'1px solid rgba(0,168,89,.2)'}}>
                  <p className="text-xs font-bold uppercase tracking-widest" style={{color:'rgba(255,255,255,.4)'}}>Selecciona tu banco</p>
                  <div className="grid grid-cols-2 gap-2">
                    {['Bancolombia','Davivienda','BBVA','Banco de Bogotá','Banco Popular','Colpatria','Occidente','Caja Social','Itaú','Scotiabank'].map(b=>(
                      <button key={b} type="button"
                        onClick={()=>setPseBank(b)}
                        className="flex items-center gap-2 px-3 py-2.5 rounded-xl text-left text-xs font-semibold transition-all"
                        style={{
                          background: pseBank===b ? 'rgba(0,168,89,.2)' : 'rgba(255,255,255,.05)',
                          border: pseBank===b ? '1.5px solid rgba(0,168,89,.5)' : '1.5px solid rgba(255,255,255,.08)',
                          color: pseBank===b ? '#6ee7b7' : 'rgba(255,255,255,.6)',
                        }}>
                        {pseBank===b && <i className="fa-solid fa-check text-emerald-400" style={{fontSize:'9px'}}></i>}
                        {b}
                      </button>
                    ))}
                  </div>
                  {pseBank && <p className="text-xs text-emerald-400 font-medium"><i className="fa-solid fa-circle-check mr-1"></i>{pseBank} seleccionado</p>}
                </div>
              )}

              {/* Nequi / Daviplata */}
              {(payMethod==='nequi'||payMethod==='daviplata')&&(
                <div className="rounded-2xl p-4 space-y-2"
                  style={{background:payMethod==='nequi'?'rgba(123,47,190,.08)':'rgba(232,0,28,.08)',
                          border:payMethod==='nequi'?'1px solid rgba(123,47,190,.25)':'1px solid rgba(232,0,28,.25)'}}>
                  <p className="text-xs font-bold uppercase tracking-widest" style={{color:'rgba(255,255,255,.4)'}}>
                    Número {payMethod==='nequi'?'Nequi':'Daviplata'}
                  </p>
                  <input type="tel" placeholder="300 000 0000" className={iCls} style={iSty}/>
                  <p className="text-xs" style={{color:'rgba(255,255,255,.3)'}}>Recibirás una notificación en tu app para confirmar.</p>
                </div>
              )}

              {/* Efecty / Bancolombia */}
              {(payMethod==='efecty'||payMethod==='bancolombia')&&(
                <div className="rounded-2xl p-4"
                  style={{background:payMethod==='efecty'?'rgba(255,184,0,.08)':'rgba(253,218,36,.08)',
                          border:payMethod==='efecty'?'1px solid rgba(255,184,0,.25)':'1px solid rgba(253,218,36,.25)'}}>
                  <p className="text-xs font-bold uppercase tracking-widest mb-2" style={{color:'rgba(255,255,255,.4)'}}>Instrucciones</p>
                  {payMethod==='efecty'
                    ? <p className="text-xs leading-relaxed" style={{color:'rgba(255,255,255,.5)'}}>Ve al punto Efecty más cercano y paga con el número de pedido que recibirás por email.</p>
                    : <p className="text-xs leading-relaxed" style={{color:'rgba(255,255,255,.5)'}}>Transfiere a <span className="font-bold text-white">Bancolombia Ahorros 123-456789-00</span>. Envía el comprobante al WhatsApp del negocio.</p>
                  }
                </div>
              )}
            </div>

            {/* Footer fijo — total + botones */}
            <div className="px-6 pb-6 pt-3 space-y-3" style={{borderTop:'1px solid rgba(255,255,255,.07)'}}>
              <div className="flex justify-between items-center rounded-2xl px-4 py-3"
                style={{background:'linear-gradient(90deg,rgba(59,89,255,.12),rgba(123,47,190,.12))',border:'1px solid rgba(59,89,255,.18)'}}>
                <span className="text-sm" style={{color:'rgba(255,255,255,.5)'}}>Total</span>
                <span className="font-black text-xl text-white">{fmt(total)}</span>
              </div>
              <div className="flex gap-3">
                <button onClick={()=>{setStep(2);setErr('');}} className="flex-1 py-3.5 rounded-2xl text-sm font-bold"
                  style={{background:'rgba(255,255,255,.06)',border:'1px solid rgba(255,255,255,.08)',color:'rgba(255,255,255,.5)'}}>
                  <i className="fa-solid fa-arrow-left mr-1 text-xs"></i> Volver
                </button>
                <button onClick={submit} disabled={loading||!payMethod}
                  className="flex-[2] py-3.5 rounded-2xl text-white font-black text-sm flex items-center justify-center gap-2 transition-all hover:scale-[1.02] disabled:opacity-50"
                  style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE)',boxShadow:payMethod?'0 8px 30px rgba(59,89,255,.45)':'none'}}>
                  {loading
                    ? <><i className="fa-solid fa-spinner fa-spin"></i> Procesando...</>
                    : <><i className="fa-solid fa-shield-halved text-xs"></i> Pagar {fmt(total)}</>
                  }
                </button>
              </div>
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

function SuccessModal({ orderId, onClose }) {
  return (
    <div className="fixed inset-0 flex items-center justify-center p-4" style={{zIndex:70}}>
      <div className="absolute inset-0" onClick={onClose}
        style={{background:'rgba(0,0,0,.75)',backdropFilter:'blur(10px)',WebkitBackdropFilter:'blur(10px)'}} />
      <div className="bounce-in relative w-full max-w-sm overflow-hidden text-center"
        style={{
          background:'linear-gradient(160deg,#0f0f1a,#13132b)',
          border:'1px solid rgba(255,255,255,.1)',
          borderRadius:'28px',
          boxShadow:'0 30px 80px rgba(59,89,255,.4)',
        }}>
        {/* Top glow */}
        <div className="absolute top-0 left-1/2 -translate-x-1/2 w-40 h-40 rounded-full pointer-events-none"
          style={{background:'radial-gradient(circle,rgba(59,89,255,.3) 0%,transparent 70%)',top:'-40px'}} />

        <div className="relative px-8 py-10">
          {/* Check icon */}
          <div className="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5 relative"
            style={{background:'linear-gradient(135deg,#3B59FF,#7B2FBE)',boxShadow:'0 0 40px rgba(59,89,255,.5)'}}>
            <i className="fa-solid fa-check text-white text-3xl"></i>
            {/* Ring */}
            <div className="absolute inset-0 rounded-full" style={{border:'2px solid rgba(255,255,255,.2)',animation:'pulse 2s infinite'}}></div>
          </div>

          <h3 className="font-black text-white text-2xl mb-2">¡Pedido listo!</h3>
          <p className="text-sm mb-1" style={{color:'rgba(255,255,255,.5)'}}>
            Tu pedido fue recibido con éxito
          </p>
          <div className="inline-flex items-center gap-2 rounded-xl px-4 py-2 my-4"
            style={{background:'rgba(59,89,255,.15)',border:'1px solid rgba(59,89,255,.3)'}}>
            <i className="fa-solid fa-hashtag text-xs" style={{color:'#3B59FF'}}></i>
            <span className="font-black text-white text-lg">{orderId}</span>
          </div>
          <p className="text-xs mb-6" style={{color:'rgba(255,255,255,.35)'}}>
            Nos pondremos en contacto contigo pronto para coordinar la entrega.
          </p>
          <button onClick={onClose}
            className="w-full py-4 rounded-2xl text-white font-black text-sm transition-all hover:scale-[1.02]"
            style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE)',boxShadow:'0 8px 30px rgba(59,89,255,.4)'}}>
            <i className="fa-solid fa-bag-shopping mr-2"></i> Seguir comprando
          </button>
        </div>
      </div>
    </div>
  );
}

// ── Navbar ─────────────────────────────────────────────────────────────────
function Navbar({ cartCount, onCartOpen }) {
  const [open, setOpen] = useState(false);
  const auth = window.__AUTH__ || {};
  const links = [
    { label:'Inicio',       href:'#inicio' },
    { label:'Categorías',   href:'#categorias' },
    { label:'Productos',    href:'#productos' },
    { label:'Ofertas',      href:'#ofertas' },
    { label:'Inspiración',  href:'#inspiracion' },
    { label:'Contacto',     href:'#contacto' },
  ];

  return (
    <nav className="fixed top-0 left-0 right-0 z-50 bg-black/95 backdrop-blur-sm border-b border-white/10">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          <a href="/" className="flex items-center gap-2 flex-shrink-0">
            <div className="w-8 h-8 bg-[#3B59FF] rounded-lg flex items-center justify-center">
              <i className="fa-solid fa-shirt text-white text-sm"></i>
            </div>
            <span className="text-white font-black text-xl">Fifty<span className="text-[#3B59FF]">One</span></span>
          </a>
          <div className="hidden md:flex items-center gap-7">
            {links.map(l => <a key={l.label} href={l.href} className="text-gray-300 hover:text-[#3B59FF] text-sm font-medium transition-colors">{l.label}</a>)}
          </div>
          <div className="flex items-center gap-2">
            <button onClick={onCartOpen}
              className="relative flex items-center gap-2 text-white text-sm font-semibold px-4 py-2 rounded-xl transition"
              style={{background:gradientBg}}>
              <i className="fa-solid fa-bag-shopping"></i>
              <span className="hidden sm:inline">Carrito</span>
              {cartCount > 0 && (
                <span className="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">{cartCount}</span>
              )}
            </button>
            {auth.loggedIn ? (
              <a href="/mi-cuenta"
                 className="hidden sm:inline-flex items-center gap-2 text-white text-sm font-semibold px-3 py-2 rounded-xl border border-white/20 hover:bg-white/10 transition">
                <span className="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black text-white flex-shrink-0"
                      style={{background:gradientBg}}>
                  {auth.name ? auth.name.charAt(0).toUpperCase() : 'U'}
                </span>
                <span>Hola, {auth.name ? auth.name.split(' ')[0] : ''}</span>
              </a>
            ) : (
              <a href="/login"
                 className="hidden sm:inline-flex items-center gap-1.5 text-white text-sm font-semibold px-3 py-2 rounded-xl border border-white/20 hover:bg-white/10 transition">
                <i className="fa-solid fa-right-to-bracket text-xs"></i>
                <span>Iniciar sesión</span>
              </a>
            )}
            <button onClick={() => setOpen(!open)} className="md:hidden text-gray-300 p-2">
              <i className={`fa-solid ${open ? 'fa-xmark' : 'fa-bars'} text-lg`}></i>
            </button>
          </div>
        </div>
      </div>
      {open && (
        <div className="md:hidden bg-black border-t border-white/10 px-4 py-4 space-y-3">
          {links.map(l => <a key={l.label} href={l.href} className="block text-gray-300 hover:text-[#3B59FF] text-sm font-medium py-1">{l.label}</a>)}
        </div>
      )}
    </nav>
  );
}

// ── Hero ───────────────────────────────────────────────────────────────────
function Hero() {
  return (
    <section id="inicio" className="hero-bg min-h-screen flex items-center relative overflow-hidden pt-16">
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute -top-40 -right-40 w-96 h-96 bg-[#3B59FF]/10 rounded-full blur-3xl"></div>
        <div className="absolute -bottom-40 -left-40 w-96 h-96 bg-[#1A237E]/10 rounded-full blur-3xl"></div>
        <div className="absolute top-1/3 left-1/4 w-72 h-72 bg-[#7B2FBE]/15 rounded-full blur-3xl"></div>
      </div>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div className="grid lg:grid-cols-2 gap-12 items-center py-20">
          <div>
            <span className="fade-up inline-flex items-center gap-2 bg-[#3B59FF]/20 text-[#3B59FF] text-xs font-semibold px-3 py-1.5 rounded-full mb-6 border border-[#3B59FF]/30">
              <i className="fa-solid fa-fire"></i> Colección Oversize 2026
            </span>
            <h1 className="fade-up-2 text-5xl sm:text-6xl lg:text-7xl font-black text-white leading-tight mb-6">
              Viste<br /><span className="text-[#3B59FF]">diferente.</span><br />Viste grande.
            </h1>
            <p className="fade-up-3 text-gray-300 text-lg mb-8 max-w-md leading-relaxed">
              Ropa oversize con estilo streetwear. Prendas cómodas, telas premium y diseños únicos para quienes marcan tendencia.
            </p>
            <div className="fade-up-3 flex flex-wrap gap-4">
              <a href="#productos" className="inline-flex items-center gap-2 text-white font-bold px-8 py-4 rounded-xl transition-all hover:scale-105 shadow-lg"
                style={{background:gradientBg}}>
                <i className="fa-solid fa-bag-shopping"></i> Ver colección
              </a>
              <a href="#categorias" className="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-bold px-8 py-4 rounded-xl border border-white/20 transition-all hover:scale-105">
                <i className="fa-solid fa-grid-2"></i> Categorías
              </a>
            </div>
            <div className="mt-12 flex gap-8">
              {[
                [dbProducts.length + '+', 'Productos'],
                [window.__STATS__?.customers || '500+', 'Clientes'],
                ['4.9★', 'Valoración']
              ].map(([n,l]) => (
                <div key={l}><p className="text-2xl font-black text-white">{n}</p><p className="text-sm text-gray-500">{l}</p></div>
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
                <div><p className="text-xs text-gray-600">Más vendido</p><p className="text-sm font-bold text-black">Hoodie Oversize Negro</p></div>
              </div>
              <div className="absolute -top-4 -right-4 bg-black text-white rounded-2xl px-4 py-3 shadow-2xl border border-white/20">
                <p className="text-xs text-gray-400">Descuento activo</p>
                <p className="text-2xl font-black text-[#3B59FF]">25% OFF</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

// ── Categories ─────────────────────────────────────────────────────────────
function Categories() {
  return (
    <section id="categorias" className="py-20 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <span className="text-[#3B59FF] text-sm font-semibold uppercase tracking-widest">Explora</span>
          <h2 className="text-4xl font-black text-black mt-2">Categorías</h2>
        </div>
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-5">
          {categories.map(cat => (
            <div key={cat.id} className="card-hover group relative rounded-2xl overflow-hidden cursor-pointer shadow-md">
              <div className="aspect-[4/5] relative">
                <img src={cat.img} alt={cat.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                <div className={`absolute inset-0 bg-gradient-to-t ${cat.color} opacity-75`}></div>
                <div className="absolute inset-0 flex flex-col items-center justify-end p-5 text-white">
                  <div className="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-3 border border-white/30">
                    <i className={`fa-solid ${cat.icon} text-xl`}></i>
                  </div>
                  <h3 className="font-bold text-lg">{cat.name}</h3>
                  <a href={`/catalogo?categoria=${encodeURIComponent(cat.name)}`} className="mt-3 bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-all">Ver más</a>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

// ── Product Card ───────────────────────────────────────────────────────────
function ProductCard({ product, onAdd, forceOpen, onForceOpenDone }) {
  const auth = window.__AUTH__ || {};
  const [added, setAdded]         = useState(false);
  const [selectedSize, setSize]   = useState('');
  const [selectedColor, setColor] = useState('');
  const [wishlisted, setWish]     = useState(product.wishlisted || false);
  const [showDetail, setDetail]   = useState(false);
  const [sizeError, setSizeError] = useState(false);

  React.useEffect(() => {
    if (forceOpen) { setDetail(true); if(onForceOpenDone) onForceOpenDone(); }
  }, [forceOpen]);

  const handleAdd = () => {
    if (product.sizes && product.sizes.length > 1 && !selectedSize) {
      setSizeError(true);
      setTimeout(() => setSizeError(false), 2000);
      return;
    }
    setSizeError(false);
    setAdded(true);
    onAdd({...product, selectedSize, selectedColor});
    setTimeout(() => setAdded(false), 1500);
  };

  const toggleWish = async () => {
    if (!auth.loggedIn) { window.location.href = '/login'; return; }
    try {
      const res = await fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
        body: JSON.stringify({product_id: product.id}),
      });
      const data = await res.json();
      setWish(data.wishlisted);
    } catch(e) {}
  };

  return (
    <div className="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200 group transition-all relative">
      {/* Wishlist button */}
      <button onClick={toggleWish}
              className="absolute top-3 right-3 z-10 w-8 h-8 rounded-full flex items-center justify-center transition-all"
              style={{background: wishlisted ? 'rgba(239,68,68,.9)' : 'rgba(255,255,255,.85)', backdropFilter:'blur(4px)'}}>
        <i className={`fa-heart text-sm ${wishlisted ? 'fa-solid text-white' : 'fa-regular text-gray-400'}`}></i>
      </button>

      <div className="relative aspect-square overflow-hidden bg-gray-100 cursor-pointer" onClick={() => setDetail(true)}>
        <img src={product.img} alt={product.name} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
        {product.badge && (
          <span className={`absolute top-3 left-3 text-xs font-bold px-2.5 py-1 rounded-full ${product.badge === 'Oferta' ? 'bg-red-500 text-white' : 'bg-[#3B59FF] text-white'}`}>
            {product.badge}
          </span>
        )}
        {product.stock === 0 && (
          <div className="absolute inset-0 bg-black/50 flex items-center justify-center">
            <span className="text-white font-bold text-sm bg-black/70 px-3 py-1 rounded-full">Agotado</span>
          </div>
        )}
        {product.stock > 0 && product.stock <= 3 && (
          <span className="absolute bottom-3 left-3 text-xs font-bold px-2 py-0.5 rounded-full bg-amber-500 text-white">
            ¡Solo {product.stock} left!
          </span>
        )}
      </div>

      <div className="p-4 flex flex-col gap-2">
        <Stars count={product.rating} />
        <h3 className="font-semibold text-black text-sm leading-tight cursor-pointer hover:text-[#3B59FF] transition-colors" onClick={() => setDetail(true)}>{product.name}</h3>

        {/* Tallas */}
        {product.sizes && product.sizes.length > 1 && (
          <div className="flex flex-col gap-1">
            <div className="flex flex-wrap gap-1">
              {product.sizes.map(s => (
                <button key={s} onClick={() => { setSize(s); setSizeError(false); }}
                        className="text-xs px-2 py-0.5 rounded-lg border transition-all font-medium"
                        style={{
                          background: selectedSize===s ? 'linear-gradient(90deg,#3B59FF,#7B2FBE)' : 'transparent',
                          color: selectedSize===s ? 'white' : '#6b7280',
                          borderColor: selectedSize===s ? 'transparent' : sizeError ? '#ef4444' : '#e5e7eb',
                        }}>
                  {s}
                </button>
              ))}
            </div>
            {sizeError && <p className="text-xs text-red-500 font-medium">⚠ Selecciona una talla</p>}
          </div>
        )}

        <span className="text-xl font-black text-[#3B59FF]">{fmt(product.price)}</span>
        <button onClick={handleAdd} disabled={product.stock === 0}
                className="w-full flex items-center justify-center gap-2 text-sm font-bold px-4 py-2.5 rounded-xl transition-all text-white hover:scale-[1.02] active:scale-95 mt-1 disabled:opacity-50 disabled:cursor-not-allowed"
                style={{background: added ? 'linear-gradient(90deg,#10b981,#059669)' : product.stock===0 ? '#9ca3af' : 'linear-gradient(90deg,#3B59FF,#7B2FBE)',
                        boxShadow: added ? '0 4px 15px rgba(16,185,129,.4)' : '0 4px 15px rgba(59,89,255,.3)'}}>
          <i className={`fa-solid ${added ? 'fa-check' : 'fa-cart-shopping'} text-xs`}></i>
          {added ? '¡Agregado!' : product.stock===0 ? 'Agotado' : 'Agregar al carrito'}
        </button>
      </div>

      {/* Modal detalle producto */}
      {showDetail && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4" onClick={() => setDetail(false)}
             style={{background:'rgba(0,0,0,.7)',backdropFilter:'blur(8px)'}}>
          <div className="bg-white rounded-3xl overflow-hidden max-w-lg w-full shadow-2xl" onClick={e => e.stopPropagation()}>
            <div className="relative aspect-video overflow-hidden bg-gray-100">
              <img src={product.img} alt={product.name} className="w-full h-full object-cover" />
              <button onClick={() => setDetail(false)} className="absolute top-3 right-3 w-9 h-9 rounded-full bg-white/90 flex items-center justify-center shadow">
                <i className="fa-solid fa-xmark text-gray-700"></i>
              </button>
            </div>
            <div className="p-6">
              <div className="flex items-start justify-between gap-3 mb-3">
                <h2 className="text-xl font-black text-gray-900">{product.name}</h2>
                <span className="text-2xl font-black text-[#3B59FF] flex-shrink-0">{fmt(product.price)}</span>
              </div>
              <Stars count={product.rating} />
              <p className="text-xs text-gray-400 mt-1">{product.reviews} reseña(s)</p>
              {product.sizes && product.sizes.length > 1 && (
                <div className="mt-4">
                  <p className="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Talla</p>
                  <div className="flex flex-wrap gap-2">
                    {product.sizes.map(s => (
                      <button key={s} onClick={() => setSize(s)}
                              className="px-3 py-1.5 rounded-xl border-2 text-sm font-bold transition-all"
                              style={{borderColor: selectedSize===s ? '#3B59FF' : '#e5e7eb', color: selectedSize===s ? '#3B59FF' : '#6b7280', background: selectedSize===s ? 'rgba(59,89,255,.05)' : 'white'}}>
                        {s}
                      </button>
                    ))}
                  </div>
                </div>
              )}
              {product.stock > 0 && product.stock <= 5 && (
                <p className="text-xs text-amber-600 font-semibold mt-3">⚡ Solo quedan {product.stock} unidades</p>
              )}
              {/* Botones compartir */}
              <div className="flex gap-2 mt-4">
                <a href={`https://wa.me/?text=${encodeURIComponent('👕 *FiftyOne - Ropa Oversize*\n\nTe comparto este producto que me pareció increíble:\n\n*' + product.name + '*\nPrecio: ' + fmt(product.price) + '\n\n¡Visita nuestra tienda y encuentra tu talla! 👉 ' + window.location.origin)}`}
                   target="_blank" rel="noopener"
                   className="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-white text-xs font-bold transition hover:opacity-80"
                   style={{background:'#25D366'}}>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="14" height="14"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                  WhatsApp
                </a>
                <button onClick={() => { navigator.clipboard.writeText(window.location.origin + '/#productos'); alert('¡Enlace copiado!'); }}
                        className="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-white text-xs font-bold transition hover:opacity-80"
                        style={{background:'linear-gradient(135deg,#833ab4,#fd1d1d,#fcb045)'}}>
                  <i className="fa-brands fa-instagram text-sm"></i>
                  Compartir
                </button>
              </div>
              <button onClick={() => { handleAdd(); setDetail(false); }} disabled={product.stock===0}
                      className="w-full mt-5 py-3.5 rounded-2xl text-white font-black text-sm flex items-center justify-center gap-2 transition hover:opacity-90 disabled:opacity-50"
                      style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE)',boxShadow:'0 8px 25px rgba(59,89,255,.35)'}}>
                <i className="fa-solid fa-cart-shopping text-xs"></i>
                {product.stock===0 ? 'Agotado' : 'Agregar al carrito'}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

// ── Products Section ───────────────────────────────────────────────────────
function Products({ onAdd }) {
  const [activeCategory, setActiveCategory] = React.useState('Todos');
  const [openProductId, setOpenProductId]   = React.useState(null);

  // Polling liviano para detectar producto seleccionado desde el buscador
  React.useEffect(() => {
    const interval = setInterval(() => {
      if (window.__openProductId__) {
        setOpenProductId(window.__openProductId__);
        window.__openProductId__ = null;
      }
    }, 100);
    return () => clearInterval(interval);
  }, []);
  const allCats = ['Todos', 'Hoodies', 'Camisetas', 'Pantalones', 'Accesorios'];
  // Mapeo de nombre de categoría a category_id real de la BD
  const catMap = { 'Hoodies':1, 'Camisetas':2, 'Pantalones':3, 'Accesorios':4 };
  const filtered = activeCategory === 'Todos'
    ? dbProducts
    : dbProducts.filter(p => p.category_id === catMap[activeCategory]);

  return (
    <section id="productos" className="py-20 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
          <div>
            <span className="text-[#3B59FF] text-sm font-semibold uppercase tracking-widest">Productos</span>
            <h2 className="text-4xl font-black text-black mt-2">Prendas destacadas</h2>
          </div>
          <a href="/productos" className="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-[#3B59FF] transition-colors">
            Ver todos <i className="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
        {/* Filtros por categoría */}
        <div className="flex flex-wrap gap-2 mb-8">
          {allCats.map(cat => (
            <button key={cat} onClick={() => setActiveCategory(cat)}
                    className="px-4 py-2 rounded-full text-sm font-semibold transition-all"
                    style={{
                      background: activeCategory===cat ? 'linear-gradient(90deg,#3B59FF,#7B2FBE)' : 'transparent',
                      color: activeCategory===cat ? 'white' : '#6b7280',
                      border: activeCategory===cat ? 'none' : '1.5px solid #e5e7eb',
                    }}>
              {cat}
            </button>
          ))}
        </div>
        <div className="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-5">
          {filtered.map(p => <ProductCard key={p.id} product={p} onAdd={onAdd}
            forceOpen={openProductId === p.id}
            onForceOpenDone={() => setOpenProductId(null)} />)}
        </div>
      </div>
    </section>
  );
}

// ── Promo ──────────────────────────────────────────────────────────────────
function PromoBanner() {
  return (
    <section id="ofertas" className="py-20 bg-black relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div className="border border-[#3B59FF]/30 rounded-3xl p-10 md:p-16 flex flex-col md:flex-row items-center justify-between gap-8"
          style={{background:'linear-gradient(135deg,rgba(59,89,255,.15),rgba(26,35,126,.35))'}}>
          <div>
            <span className="inline-flex items-center gap-2 bg-[#3B59FF]/20 text-[#3B59FF] text-xs font-bold px-3 py-1.5 rounded-full border border-[#3B59FF]/30 mb-4">
              <i className="fa-solid fa-bolt"></i> Oferta por tiempo limitado
            </span>
            <h2 className="text-4xl md:text-5xl font-black text-white leading-tight">
              25% OFF<br /><span className="text-[#3B59FF]">en toda la colección</span>
            </h2>
            <p className="text-gray-300 mt-4 max-w-md">
              Usa el código <code className="bg-white/10 text-[#3B59FF] font-mono font-bold px-2 py-0.5 rounded">FIFTY25</code> al finalizar tu compra.
            </p>
          </div>
          <a href="#productos" className="inline-flex items-center gap-2 text-white font-bold px-8 py-4 rounded-xl transition-all hover:scale-105 flex-shrink-0"
            style={{background:gradientBg}}>
            <i className="fa-solid fa-bag-shopping"></i> Aprovechar oferta
          </a>
        </div>
      </div>
    </section>
  );
}

// ── Testimonials / Reviews ─────────────────────────────────────────────────
function Testimonials() {
  const auth = window.__AUTH__ || {};
  const [reviews, setReviews] = React.useState(testimonials);
  const [rating, setRating] = React.useState(5);
  const [comment, setComment] = React.useState('');
  const [productId, setProductId] = React.useState(dbProducts[0]?.id || '');
  const [sending, setSending] = React.useState(false);
  const [sent, setSent] = React.useState(false);

  const submitReview = async () => {
    if (!auth.loggedIn) { alert('Debes iniciar sesión para dejar una reseña.'); return; }
    setSending(true);
    try {
      const res = await fetch('/reviews', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ product_id: productId, rating, comment }),
      });
      if (res.ok) {
        setSent(true);
        setComment('');
        setTimeout(() => setSent(false), 3000);
        // Recargar reseñas
        const r = await fetch('/reviews?product_id=' + productId);
        const data = await r.json();
        if (data.length) setReviews(prev => [...data.map(d => ({name:d.user, product:d.product||'', rating:d.rating, comment:d.comment, date:d.date})), ...prev].slice(0,6));
      }
    } catch(e) {}
    setSending(false);
  };

  return (
    <section className="py-20 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <span className="text-[#3B59FF] text-sm font-semibold uppercase tracking-widest">Opiniones</span>
          <h2 className="text-4xl font-black text-black mt-2">Lo que dicen nuestros clientes</h2>
        </div>

        {/* Formulario reseña — siempre visible */}
        <div className="max-w-xl mx-auto mb-12 bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
          <h3 className="font-bold text-gray-800 mb-1">Deja tu opinión</h3>
          <p className="text-xs text-gray-400 mb-4">Tu experiencia ayuda a otros clientes</p>

          {auth.loggedIn ? (
            <>
              <select value={productId} onChange={e => setProductId(e.target.value)}
                      className="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                {dbProducts.map(p => <option key={p.id} value={p.id}>{p.name}</option>)}
              </select>
              <div className="flex gap-2 mb-3">
                {[1,2,3,4,5].map(s => (
                  <button key={s} type="button" onClick={() => setRating(s)} className="text-3xl transition-transform hover:scale-125 focus:outline-none">
                    <i className={`fa-star ${s <= rating ? 'fa-solid text-amber-400' : 'fa-regular text-gray-300'}`}></i>
                  </button>
                ))}
                <span className="ml-2 text-sm text-gray-500 self-center">{rating} de 5</span>
              </div>
              <textarea value={comment} onChange={e => setComment(e.target.value)}
                        placeholder="Cuéntanos tu experiencia con el producto..." rows={3}
                        className="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm mb-3 focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none"></textarea>
              <button onClick={submitReview} disabled={sending}
                      className="w-full py-3 rounded-xl text-white text-sm font-bold transition hover:opacity-90 flex items-center justify-center gap-2"
                      style={{background: sent ? 'linear-gradient(90deg,#10b981,#059669)' : 'linear-gradient(90deg,#3B59FF,#7B2FBE)'}}>
                <i className={`fa-solid ${sent ? 'fa-check' : sending ? 'fa-spinner fa-spin' : 'fa-star'} text-xs`}></i>
                {sent ? '¡Reseña publicada!' : sending ? 'Enviando...' : 'Publicar reseña'}
              </button>
            </>
          ) : (
            <div className="text-center py-4">
              <div className="flex justify-center gap-1 mb-3">
                {[1,2,3,4,5].map(s => <i key={s} className="fa-solid fa-star text-2xl text-amber-400"></i>)}
              </div>
              <p className="text-gray-500 text-sm mb-4">Inicia sesión para dejar tu opinión</p>
              <a href="/login"
                 className="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-bold transition hover:opacity-90"
                 style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE)'}}>
                <i className="fa-solid fa-right-to-bracket text-xs"></i>
                Iniciar sesión
              </a>
            </div>
          )}
        </div>

        {/* Lista reseñas */}
        {reviews.length > 0 ? (
          <div className="grid md:grid-cols-3 gap-6">
            {reviews.map((t,i) => (
              <div key={i} className="card-hover bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                <Stars count={t.rating || t.stars || 5} />
                <p className="text-gray-600 mt-4 leading-relaxed text-sm">"{t.comment || t.text || 'Sin comentario'}"</p>
                <div className="flex items-center gap-3 mt-6">
                  <div className="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                    style={{background:gradientBg}}>{(t.name||'U').charAt(0).toUpperCase()}</div>
                  <div>
                    <p className="font-semibold text-black text-sm">{t.name}</p>
                    <p className="text-gray-500 text-xs">{t.product || ''} {t.date ? '· '+t.date : ''}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="text-center py-8 text-gray-400">
            <p className="text-sm">Aún no hay reseñas. ¡Sé el primero!</p>
          </div>
        )}
      </div>
    </section>
  );
}

// ── Footer ─────────────────────────────────────────────────────────────────
function Footer() {
  return (
    <footer id="contacto" className="bg-black text-gray-500 pt-16 pb-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-2 md:grid-cols-4 gap-10 mb-12">
          <div className="col-span-2 md:col-span-1">
            <div className="flex items-center gap-2 mb-4">
              <div className="w-8 h-8 bg-[#3B59FF] rounded-lg flex items-center justify-center">
                <i className="fa-solid fa-shirt text-white text-sm"></i>
              </div>
              <span className="text-white font-black text-xl">Fifty<span className="text-[#3B59FF]">One</span></span>
            </div>
            <p className="text-sm leading-relaxed mb-5 text-gray-400">Tu tienda de ropa oversize. Estilo streetwear, telas premium y envíos a todo el país.</p>
          </div>
          {[
            { title:'Tienda',  links:[
              {l:'Hoodies',    h:'/catalogo?categoria=Hoodies'},
              {l:'Camisetas',  h:'/catalogo?categoria=Camisetas'},
              {l:'Pantalones', h:'/catalogo?categoria=Pantalones'},
              {l:'Accesorios', h:'/catalogo?categoria=Accesorios'},
            ]},
            { title:'Empresa', links:[
              {l:'Sobre nosotros',       h:'/sobre-nosotros'},
              {l:'Blog',                 h:'/blog'},
              {l:'Trabaja con nosotros', h:'/trabaja-con-nosotros'},
            ]},
            { title:'Soporte', links:[
              {l:'Contacto',     h:'/contacto'},
              {l:'Envíos',       h:'/envios'},
              {l:'Devoluciones', h:'/devoluciones'},
              {l:'FAQ',          h:'/faq'},
            ]},
          ].map(col => (
            <div key={col.title}>
              <h4 className="text-white font-semibold text-sm mb-4">{col.title}</h4>
              <ul className="space-y-2.5">
                {col.links.map(item => <li key={item.l}><a href={item.h} className="text-sm text-gray-400 hover:text-[#3B59FF] transition-colors">{item.l}</a></li>)}
              </ul>
            </div>
          ))}
        </div>
        <div className="border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p className="text-xs text-gray-600">© 2026 FiftyOne. Todos los derechos reservados.</p>
          <div className="flex items-center gap-4 text-xs text-gray-400">
            <a href="/privacidad" className="hover:text-[#3B59FF] transition-colors">Privacidad</a>
            <a href="/terminos" className="hover:text-[#3B59FF] transition-colors">Términos</a>
          </div>
        </div>
      </div>
    </footer>
  );
}

// ── Top Banner ─────────────────────────────────────────────────────────────
function TopBanner() {
  const [visible, setVisible] = React.useState(true);
  if (!visible) return null;
  return (
    <div className="relative overflow-hidden text-white text-xs font-semibold py-2.5 flex items-center justify-center gap-3"
         style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE,#3B59FF)',backgroundSize:'200% 100%',animation:'shimmer 3s infinite'}}>
      <span>🚚 Envío gratis en compras mayores a $200.000</span>
      <span className="opacity-40">·</span>
      <span>Usa el código <strong className="bg-white/20 px-2 py-0.5 rounded-md tracking-widest">FIFTY25</strong> y obtén 25% de descuento</span>
      <button onClick={() => setVisible(false)}
              className="absolute right-3 top-1/2 -translate-y-1/2 opacity-50 hover:opacity-100 transition">
        <i className="fa-solid fa-xmark text-xs"></i>
      </button>
    </div>
  );
}

// ── How To Buy ─────────────────────────────────────────────────────────────
function HowToBuy() {
  const steps = [
    { icon:'fa-shirt',         color:'#3B59FF', title:'Elige tu prenda',  desc:'Explora el catálogo, selecciona tu talla y color favorito.' },
    { icon:'fa-shield-halved', color:'#7B2FBE', title:'Paga seguro',      desc:'Acepta Nequi, Daviplata, PSE, Bancolombia y tarjetas.' },
    { icon:'fa-house',         color:'#059669', title:'Recibe en casa',   desc:'Envíos a todo Colombia en 3 a 5 días hábiles.' },
  ];
  return (
    <section className="py-16 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-10">
          <span className="text-[#3B59FF] text-sm font-semibold uppercase tracking-widest">Simple y rápido</span>
          <h2 className="text-3xl font-black text-gray-900 mt-2">¿Cómo comprar?</h2>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {steps.map((s,i) => (
            <div key={i} className="text-center group">
              <div className="relative inline-flex items-center justify-center w-20 h-20 rounded-3xl mb-5 transition-transform group-hover:scale-110 duration-300"
                   style={{background:`${s.color}18`}}>
                <i className={`fa-solid ${s.icon} text-2xl`} style={{color:s.color}}></i>
                <span className="absolute -top-2 -right-2 w-7 h-7 rounded-full flex items-center justify-center text-xs font-black text-white"
                      style={{background:s.color}}>{i+1}</span>
              </div>
              <h3 className="font-bold text-gray-900 text-lg mb-2">{s.title}</h3>
              <p className="text-gray-500 text-sm leading-relaxed">{s.desc}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

// ── Looks del Mes ──────────────────────────────────────────────────────────
function LooksDelMes({ onAdd }) {
  const auth = window.__AUTH__ || {};

  // Semilla basada en el día actual — cambia cada día automáticamente
  const today = new Date();
  const seed  = today.getFullYear() * 10000 + (today.getMonth()+1) * 100 + today.getDate();

  // Generador pseudoaleatorio determinista con semilla
  const rng = (s) => { let x = Math.sin(s) * 10000; return x - Math.floor(x); };

  // Separar productos por categoría
  const hoodies    = dbProducts.filter(p => p.category_id === 1);
  const camisetas  = dbProducts.filter(p => p.category_id === 2);
  const pantalones = dbProducts.filter(p => p.category_id === 3);
  const accesorios = dbProducts.filter(p => p.category_id === 4);

  const pick = (arr, s) => arr[Math.floor(rng(s) * arr.length)] || arr[0];

  const looks = [
    {
      title: 'Look Urbano',
      desc: 'Sudadera + Pantalón + Accesorio',
      products: [
        pick(hoodies,    seed + 1),
        pick(pantalones, seed + 2),
        pick(accesorios, seed + 3),
      ].filter(Boolean),
      bg: 'from-[#0d0d1a] to-[#1a0a2e]',
    },
    {
      title: 'Look Casual',
      desc: 'Camiseta + Pantalón + Accesorio',
      products: [
        pick(camisetas,  seed + 4),
        pick(pantalones, seed + 5),
        pick(accesorios, seed + 6),
      ].filter(Boolean),
      bg: 'from-[#0a0e2e] to-[#0d0d1a]',
    },
    {
      title: 'Look Femenino',
      desc: 'Sudadera Crop + Pantalón + Accesorio',
      products: [
        pick(hoodies.filter(p => p.name.toLowerCase().includes('crop') || p.name.toLowerCase().includes('mujer')) || hoodies, seed + 7),
        pick(pantalones, seed + 8),
        pick(accesorios, seed + 9),
      ].filter(Boolean),
      bg: 'from-[#1a0a2e] to-[#0a0e2e]',
    },
  ];

  // Fecha legible
  const fechaHoy = today.toLocaleDateString('es-CO', { weekday:'long', day:'numeric', month:'long' });

  return (
    <section id="inspiracion" className="py-20 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <span className="text-[#3B59FF] text-sm font-semibold uppercase tracking-widest">Inspiración</span>
          <h2 className="text-4xl font-black text-gray-900 mt-2">Looks del día</h2>
          <p className="text-gray-500 mt-2 capitalize">Outfits de hoy · {fechaHoy}</p>
        </div>
        <div className="grid md:grid-cols-3 gap-6">
          {looks.map((look,i) => (
            <div key={i} className={`rounded-3xl overflow-hidden bg-gradient-to-br ${look.bg} p-6 text-white`}>
              <h3 className="font-black text-xl mb-1">{look.title}</h3>
              <p className="text-xs mb-5" style={{color:'rgba(255,255,255,.5)'}}>{look.desc}</p>
              <div className="space-y-3">
                {look.products.map(p => p && (
                  <div key={p.id} className="flex items-center gap-3 rounded-2xl p-3"
                       style={{background:'rgba(255,255,255,.07)',border:'1px solid rgba(255,255,255,.1)'}}>
                    <div className="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0">
                      {p.img && <img src={p.img} className="w-full h-full object-cover" alt={p.name}/>}
                    </div>
                    <div className="flex-1 min-w-0">
                      <p className="text-sm font-semibold truncate">{p.name}</p>
                      <p className="text-xs" style={{color:'rgba(255,255,255,.4)'}}>{fmt(p.price)}</p>
                    </div>
                    <button onClick={() => { if(!auth.loggedIn){window.location.href='/login';return;} onAdd(p); }}
                            className="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 transition hover:scale-110"
                            style={{background:'rgba(59,89,255,.5)'}}>
                      <i className="fa-solid fa-plus text-xs"></i>
                    </button>
                  </div>
                ))}
              </div>
              <div className="mt-4 pt-4 border-t border-white/10 flex justify-between items-center">
                <span className="text-xs" style={{color:'rgba(255,255,255,.4)'}}>Total del look</span>
                <span className="font-black text-lg">{fmt(look.products.reduce((s,p) => s + (p?.price||0), 0))}</span>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

// ── App ────────────────────────────────────────────────────────────────────
function App() {
  const [cart, setCart]           = useState(() => JSON.parse(localStorage.getItem('fiftyone_cart') || '[]'));
  const [drawerOpen, setDrawer]   = useState(false);
  const [showCheckout, setCheckout] = useState(false);
  const [successId, setSuccessId] = useState(null);

  // Abrir checkout automáticamente si viene de login con ?checkout=1
  React.useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('checkout') === '1') {
      const cart = JSON.parse(localStorage.getItem('fiftyone_cart') || '[]');
      if (cart.length > 0) {
        setCheckout(true);
        // Limpiar el parámetro de la URL sin recargar
        window.history.replaceState({}, '', '/');
      }
    }
  }, []);

  useEffect(() => {
    localStorage.setItem('fiftyone_cart', JSON.stringify(cart));
  }, [cart]);

  const totalItems = cart.reduce((s,i) => s + i.qty, 0);

  const addToCart = (product) => {
    setCart(prev => {
      const existing = prev.find(i => i.id === product.id);
      if (existing) return prev.map(i => i.id === product.id ? {...i, qty: i.qty + 1} : i);
      return [...prev, { id: product.id, name: product.name, price: product.price, img: product.img, qty: 1 }];
    });
    setDrawer(true);
  };

  const updateQty = (id, delta) => setCart(prev => prev.map(i => i.id === id ? {...i, qty: i.qty + delta} : i).filter(i => i.qty > 0));
  const removeItem = (id) => setCart(prev => prev.filter(i => i.id !== id));
  const clearCart  = ()   => setCart([]);
  const openCheckout = () => { setDrawer(false); setCheckout(true); };
  const handleSuccess = (orderId) => { setCheckout(false); clearCart(); setSuccessId(orderId); };

  return (
    <>
      <TopBanner />
      <Navbar cartCount={totalItems} onCartOpen={() => setDrawer(true)} />
      <Hero />
      <HowToBuy />
      <Categories />
      <Products onAdd={addToCart} />
      <PromoBanner />
      <LooksDelMes onAdd={addToCart} />
      <Testimonials />
      <Footer />
      <CartDrawer open={drawerOpen} onClose={() => setDrawer(false)} cart={cart}
        onUpdateQty={updateQty} onRemove={removeItem} onClear={clearCart} onCheckout={openCheckout} />
      {showCheckout && <CheckoutModal cart={cart} onClose={() => setCheckout(false)} onSuccess={handleSuccess} />}
      {successId && <SuccessModal orderId={successId} onClose={() => setSuccessId(null)} />}
      {/* Barra navegación móvil */}
      <MobileNav cartCount={totalItems} onCartOpen={() => setDrawer(true)} />
    </>
  );
}

// ── Mobile Nav Bar ─────────────────────────────────────────────────────────
function MobileNav({ cartCount, onCartOpen }) {
  const auth = window.__AUTH__ || {};
  const path = window.location.pathname;

  const items = [
    { icon:'fa-house',       label:'Inicio',    action: () => { window.location.href='/'; } },
    { icon:'fa-shirt',       label:'Productos', action: () => { document.getElementById('productos')?.scrollIntoView({behavior:'smooth'}); } },
    { icon:'fa-bag-shopping',label:'Carrito',   action: onCartOpen, badge: cartCount },
    { icon:'fa-heart',       label:'Favoritos', action: () => { if(!auth.loggedIn){window.location.href='/login';return;} window.location.href='/mi-cuenta'; } },
    { icon: auth.loggedIn ? 'fa-circle-user' : 'fa-right-to-bracket',
      label: auth.loggedIn ? 'Mi cuenta' : 'Entrar',
      action: () => { window.location.href = auth.loggedIn ? '/mi-cuenta' : '/login'; }
    },
  ];

  return (
    <div className="md:hidden fixed bottom-0 left-0 right-0 z-50 flex items-center justify-around px-2 py-2 safe-area-pb"
         style={{background:'rgba(0,0,0,.95)',backdropFilter:'blur(20px)',borderTop:'1px solid rgba(255,255,255,.08)'}}>
      {items.map((item,i) => (
        <button key={i} onClick={item.action}
                className="relative flex flex-col items-center gap-1 px-3 py-1.5 rounded-2xl transition-all active:scale-90"
                style={{color:'rgba(255,255,255,.5)'}}>
          {item.badge > 0 && (
            <span className="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-red-500 text-white text-[9px] font-black flex items-center justify-center">
              {item.badge > 9 ? '9+' : item.badge}
            </span>
          )}
          <i className={`fa-solid ${item.icon} text-lg`}
             style={{color: item.label==='Carrito' && item.badge > 0 ? '#3B59FF' : 'inherit'}}></i>
          <span className="text-[10px] font-semibold">{item.label}</span>
        </button>
      ))}
    </div>
  );
}

ReactDOM.createRoot(document.getElementById('root')).render(<App />);
</script>
@endverbatim

{{-- PWA Service Worker --}}
<script>
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js').catch(() => {});
}
</script>

{{-- Barra navegación móvil --}}
<nav id="mobile-nav" style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:9998;background:linear-gradient(135deg,#0d0d1a,#0a0e2e);border-top:1px solid rgba(255,255,255,.1);padding:8px 0 env(safe-area-inset-bottom,8px)">
    <div style="display:flex;justify-content:space-around;align-items:center">
        <a href="#inicio" onclick="closeMobileMenu()" style="display:flex;flex-direction:column;align-items:center;gap:3px;text-decoration:none;padding:4px 16px;border-radius:12px;transition:.2s" class="mob-nav-item">
            <i class="fa-solid fa-house" style="font-size:18px;color:rgba(255,255,255,.5)"></i>
            <span style="font-size:10px;color:rgba(255,255,255,.5);font-family:Inter,sans-serif;font-weight:600">Inicio</span>
        </a>
        <a href="#productos" onclick="closeMobileMenu()" style="display:flex;flex-direction:column;align-items:center;gap:3px;text-decoration:none;padding:4px 16px;border-radius:12px;transition:.2s" class="mob-nav-item">
            <i class="fa-solid fa-shirt" style="font-size:18px;color:rgba(255,255,255,.5)"></i>
            <span style="font-size:10px;color:rgba(255,255,255,.5);font-family:Inter,sans-serif;font-weight:600">Productos</span>
        </a>
        <button id="mob-cart-btn" onclick="document.getElementById('mob-cart-btn').dispatchEvent(new CustomEvent('open-cart',{bubbles:true}))" style="display:flex;flex-direction:column;align-items:center;gap:3px;background:none;border:none;cursor:pointer;padding:4px 16px;position:relative">
            <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#3B59FF,#7B2FBE);display:flex;align-items:center;justify-content:center;margin-top:-18px;box-shadow:0 4px 20px rgba(59,89,255,.5)">
                <i class="fa-solid fa-bag-shopping" style="font-size:18px;color:white"></i>
            </div>
            <span id="mob-cart-count" style="display:none;position:absolute;top:-2px;right:8px;width:18px;height:18px;background:#ef4444;border-radius:50%;font-size:10px;font-weight:700;color:white;display:flex;align-items:center;justify-content:center">0</span>
            <span style="font-size:10px;color:rgba(255,255,255,.5);font-family:Inter,sans-serif;font-weight:600">Carrito</span>
        </button>
        @if(auth()->check() && auth()->user()?->role !== 'admin')
        <a href="/mi-cuenta" style="display:flex;flex-direction:column;align-items:center;gap:3px;text-decoration:none;padding:4px 16px;border-radius:12px;transition:.2s" class="mob-nav-item">
            <i class="fa-solid fa-circle-user" style="font-size:18px;color:rgba(255,255,255,.5)"></i>
            <span style="font-size:10px;color:rgba(255,255,255,.5);font-family:Inter,sans-serif;font-weight:600">Mi cuenta</span>
        </a>
        @else
        <a href="/login" style="display:flex;flex-direction:column;align-items:center;gap:3px;text-decoration:none;padding:4px 16px;border-radius:12px;transition:.2s" class="mob-nav-item">
            <i class="fa-solid fa-right-to-bracket" style="font-size:18px;color:rgba(255,255,255,.5)"></i>
            <span style="font-size:10px;color:rgba(255,255,255,.5);font-family:Inter,sans-serif;font-weight:600">Ingresar</span>
        </a>
        @endif
    </div>
</nav>

<style>
@media (max-width: 768px) {
    #mobile-nav { display:block !important; }
    body { padding-bottom: 70px; }
    .mob-nav-item:active { background:rgba(59,89,255,.2); }
}
</style>

<script>
function closeMobileMenu() {}
// Sincronizar contador del carrito móvil
setInterval(function() {
    try {
        var cart = JSON.parse(localStorage.getItem('fiftyone_cart') || '[]');
        var count = cart.reduce(function(s,i){ return s + (i.qty||1); }, 0);
        var el = document.getElementById('mob-cart-count');
        if (el) { el.textContent = count; el.style.display = count > 0 ? 'flex' : 'none'; }
    } catch(e) {}
}, 500);
// Conectar botón carrito móvil con React
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('mob-cart-btn')?.addEventListener('open-cart', function() {
        // Simular clic en el botón de carrito del navbar
        var cartBtns = document.querySelectorAll('button');
        cartBtns.forEach(function(btn) {
            if (btn.textContent.includes('Carrito')) btn.click();
        });
    });
});
</script>

{{-- Botón flotante WhatsApp --}}
<style>
@keyframes waPulse {
    0%   { box-shadow: 0 0 0 0 rgba(37,211,102,.6); }
    70%  { box-shadow: 0 0 0 14px rgba(37,211,102,0); }
    100% { box-shadow: 0 0 0 0 rgba(37,211,102,0); }
}
.wa-btn { animation: waPulse 2s infinite; }
.wa-btn:hover { transform: scale(1.12) rotate(-5deg) !important; }
.wa-tooltip { opacity:0; transform:translateX(10px); transition:all .25s ease; pointer-events:none; }
.wa-wrap:hover .wa-tooltip { opacity:1; transform:translateX(0); }
</style>
<div class="wa-wrap" style="position:fixed;bottom:28px;right:28px;z-index:9999;display:flex;align-items:center;gap:10px;flex-direction:row-reverse">
    <a href="https://wa.me/573118422192?text=Hola%20FiftyOne%20%F0%9F%91%8B%0A%0AEstoy%20interesado%20en%20sus%20productos%20de%20ropa%20oversize.%0A%C2%BFPodr%C3%ADan%20brindarme%20informaci%C3%B3n%20sobre%20disponibilidad%2C%20tallas%20y%20tiempos%20de%20entrega%3F%0A%0AQuedo%20atento%2C%20gracias.%20%F0%9F%99%8F"
       target="_blank" rel="noopener"
       class="wa-btn"
       style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#25D366,#128C7E);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(37,211,102,.5);transition:transform .2s ease;text-decoration:none;flex-shrink:0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="32" height="32">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>
    <div class="wa-tooltip"
         style="background:linear-gradient(135deg,#0d0d1a,#0a0e2e);color:white;padding:10px 16px;border-radius:14px;font-family:Inter,sans-serif;font-size:13px;font-weight:600;white-space:nowrap;border:1px solid rgba(37,211,102,.3);box-shadow:0 8px 24px rgba(0,0,0,.3)">
        <span style="color:#25D366">●</span> ¡Chatea con nosotros!
        <div style="font-size:11px;font-weight:400;color:#94a3b8;margin-top:2px">Respondemos en minutos</div>
    </div>
</div>
</body>
</html>
