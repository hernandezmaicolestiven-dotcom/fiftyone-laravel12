<!DOCTYPE html>
<html lang="es">
@php use Illuminate\Support\Facades\Storage; @endphp
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FiftyOne — Ropa Oversize</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
</head>
<body class="bg-white text-gray-900 antialiased">
<div id="root"></div>

@verbatim
@php
$products = \App\Models\Product::with('category')->latest()->take(6)->get()->map(fn($p) => [
  'id'    => $p->id,
  'name'  => $p->name,
  'price' => (float) $p->price,
  'badge' => $p->stock < 5 ? 'Oferta' : ($p->created_at->diffInDays() < 30 ? 'Nuevo' : null),
  'img'   => $p->image ? (str_starts_with($p->image,'http') ? $p->image : Storage::url($p->image)) : 'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80',
  'rating'=> 5,
]);
@endphp
@endverbatim
<script type="text/plain" id="products-data">{!! json_encode($products) !!}</script>
@verbatim
<script type="text/babel">
const { useState, useEffect } = React;

window.__PRODUCTS__ = JSON.parse(document.getElementById('products-data').textContent);

const dbProducts   = window.__PRODUCTS__;
const gradientBg   = 'linear-gradient(90deg,#3B59FF,#7B2FBE)';

const categories = [
  { id:1, name:'Hoodies',    icon:'fa-shirt',      color:'from-[#1A237E] to-[#000000]', img:'https://images.unsplash.com/photo-1556821840-3a63f15732ce?w=400&q=80' },
  { id:2, name:'Camisetas',  icon:'fa-shirt',      color:'from-[#3B59FF] to-[#1A237E]', img:'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&q=80' },
  { id:3, name:'Pantalones', icon:'fa-person',     color:'from-[#333333] to-[#000000]', img:'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400&q=80' },
  { id:4, name:'Accesorios', icon:'fa-hat-cowboy', color:'from-[#3B59FF] to-[#000000]', img:'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=400&q=80' },
];

const testimonials = [
  { name:'Valentina R.', role:'Cliente frecuente',  text:'La calidad de las telas es increíble. Los hoodies son súper cómodos y el fit oversize queda perfecto.', avatar:'VR', stars:5 },
  { name:'Sebastián M.', role:'Streetwear lover',   text:'Llevo 3 pedidos y siempre llegan rápido y bien empacados. La camiseta boxy es mi favorita.', avatar:'SM', stars:5 },
  { name:'Camila T.',    role:'Influencer de moda', text:'FiftyOne tiene el mejor estilo oversize del mercado. Mis seguidores siempre me preguntan dónde compro.', avatar:'CT', stars:5 },
];

const fmt = n => '$' + parseFloat(n).toFixed(2);

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
            <button onClick={onCheckout}
              className="w-full py-4 rounded-2xl text-white font-black text-sm flex items-center justify-center gap-2 transition-all hover:scale-[1.02] active:scale-[.98]"
              style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE)',boxShadow:'0 8px 30px rgba(59,89,255,.4)'}}>
              <i className="fa-solid fa-lock text-xs"></i>
              Finalizar pedido · {fmt(total)}
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
  const [notes,     setNotes]     = useState('');
  const [payMethod, setPayMethod] = useState(null);
  const [pseBank,   setPseBank]   = useState('');
  const [cardNum,   setCardNum]   = useState('');
  const [cardExp,   setCardExp]   = useState('');
  const [cardCvv,   setCardCvv]   = useState('');
  const [cardName,  setCardName]  = useState('');
  const [err,       setErr]       = useState('');
  const [loading,   setLoading]   = useState(false);
  const total = cart.reduce((s,i) => s + i.price * i.qty, 0);

  const goStep2 = () => { setErr(''); setStep(2); };
  const goStep3 = () => { if (!name.trim()) { setErr('El nombre es requerido'); return; } setErr(''); setStep(3); };

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
          notes: (notes ? notes+' | ' : '')+'Pago: '+payMethod,
          items: cart.map(i => ({product_id:i.id, quantity:i.qty})),
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
            <div className="flex justify-between items-center rounded-2xl px-5 py-3.5 mb-5"
              style={{background:'linear-gradient(90deg,rgba(59,89,255,.15),rgba(123,47,190,.15))',border:'1px solid rgba(59,89,255,.2)'}}>
              <span className="text-sm font-medium" style={{color:'rgba(255,255,255,.55)'}}>Total a pagar</span>
              <span className="font-black text-2xl text-white">{fmt(total)}</span>
            </div>
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
              <label className="block text-xs font-bold mb-2 uppercase tracking-widest" style={{color:'rgba(255,255,255,.35)'}}>Notas</label>
              <textarea value={notes} onChange={e=>setNotes(e.target.value)} rows={2} placeholder="Dirección, instrucciones..." className={iCls+' resize-none'} style={iSty}/>
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
  const links = [
    { label:'Inicio', href:'#inicio' }, { label:'Productos', href:'#productos' },
    { label:'Categorías', href:'#categorias' }, { label:'Ofertas', href:'#ofertas' },
    { label:'Contacto', href:'#contacto' },
  ];
  return (
    <nav className="fixed top-0 left-0 right-0 z-50 bg-black/95 backdrop-blur-sm border-b border-white/10">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          <a href="#" className="flex items-center gap-2">
            <div className="w-8 h-8 bg-[#3B59FF] rounded-lg flex items-center justify-center">
              <i className="fa-solid fa-shirt text-white text-sm"></i>
            </div>
            <span className="text-white font-black text-xl">Fifty<span className="text-[#3B59FF]">One</span></span>
          </a>
          <div className="hidden md:flex items-center gap-7">
            {links.map(l => <a key={l.label} href={l.href} className="text-gray-300 hover:text-[#3B59FF] text-sm font-medium transition-colors">{l.label}</a>)}
          </div>
          <div className="flex items-center gap-3">
            <button onClick={onCartOpen}
              className="relative flex items-center gap-2 text-white text-sm font-semibold px-4 py-2 rounded-xl transition"
              style={{background:gradientBg}}>
              <i className="fa-solid fa-bag-shopping"></i>
              <span className="hidden sm:inline">Carrito</span>
              {cartCount > 0 && (
                <span className="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">{cartCount}</span>
              )}
            </button>
            <a href="/admin/login" className="hidden sm:inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold px-4 py-2 rounded-lg border border-white/20 transition">
              <i className="fa-solid fa-user text-xs"></i> Admin
            </a>
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
              {[['500+','Prendas'],['5K+','Clientes'],['4.9★','Valoración']].map(([n,l]) => (
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
                  <a href="/productos" className="mt-3 bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-4 py-2 rounded-lg transition-all">Ver más</a>
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
function ProductCard({ product, onAdd }) {
  const [added, setAdded] = useState(false);
  const handleAdd = () => { setAdded(true); onAdd(product); setTimeout(() => setAdded(false), 1500); };
  return (
    <div className="card-hover bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-200 group transition-all">
      <div className="relative aspect-square overflow-hidden bg-gray-100">
        <img src={product.img} alt={product.name} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
        {product.badge && (
          <span className={`absolute top-3 left-3 text-xs font-bold px-2.5 py-1 rounded-full ${product.badge === 'Oferta' ? 'bg-red-500 text-white' : 'bg-[#3B59FF] text-white'}`}>
            {product.badge}
          </span>
        )}
      </div>
      <div className="p-4">
        <Stars count={product.rating} />
        <h3 className="font-semibold text-black mt-1.5 text-sm leading-tight">{product.name}</h3>
        <div className="flex items-center justify-between mt-3">
          <span className="text-lg font-black text-black">{fmt(product.price)}</span>
          <button onClick={handleAdd}
            className="flex items-center gap-1.5 text-xs font-bold px-3 py-2 rounded-xl transition-all text-white hover:scale-105 active:scale-95"
            style={{background: added ? 'linear-gradient(90deg,#10b981,#059669)' : 'linear-gradient(90deg,#3B59FF,#7B2FBE)',boxShadow: added ? '0 4px 15px rgba(16,185,129,.4)' : '0 4px 15px rgba(59,89,255,.3)'}}>
            <i className={`fa-solid ${added ? 'fa-check' : 'fa-cart-plus'} text-xs`}></i>
            {added ? '¡Listo!' : 'Agregar'}
          </button>
        </div>
      </div>
    </div>
  );
}

// ── Products Section ───────────────────────────────────────────────────────
function Products({ onAdd }) {
  return (
    <section id="productos" className="py-20 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-12 gap-4">
          <div>
            <span className="text-[#3B59FF] text-sm font-semibold uppercase tracking-widest">Catálogo</span>
            <h2 className="text-4xl font-black text-black mt-2">Prendas destacadas</h2>
          </div>
          <a href="/productos" className="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-[#3B59FF] transition-colors">
            Ver todos <i className="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
        <div className="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-5">
          {dbProducts.map(p => <ProductCard key={p.id} product={p} onAdd={onAdd} />)}
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

// ── Testimonials ───────────────────────────────────────────────────────────
function Testimonials() {
  return (
    <section className="py-20 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <span className="text-[#3B59FF] text-sm font-semibold uppercase tracking-widest">Opiniones</span>
          <h2 className="text-4xl font-black text-black mt-2">Lo que dicen nuestros clientes</h2>
        </div>
        <div className="grid md:grid-cols-3 gap-6">
          {testimonials.map((t,i) => (
            <div key={i} className="card-hover bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
              <Stars count={t.stars} />
              <p className="text-gray-600 mt-4 leading-relaxed text-sm">"{t.text}"</p>
              <div className="flex items-center gap-3 mt-6">
                <div className="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0"
                  style={{background:gradientBg}}>{t.avatar}</div>
                <div><p className="font-semibold text-black text-sm">{t.name}</p><p className="text-gray-500 text-xs">{t.role}</p></div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}

// ── System Section ─────────────────────────────────────────────────────────
function SystemSection() {
  const features = [
    { icon:'fa-box',          color:'#3B59FF', bg:'rgba(59,89,255,.12)',  title:'Gestión de productos',   desc:'Crea, edita y organiza tu catálogo con imágenes, categorías, precios y control de stock en tiempo real.' },
    { icon:'fa-bag-shopping', color:'#7B2FBE', bg:'rgba(123,47,190,.12)', title:'Pedidos en línea',        desc:'Recibe y gestiona pedidos directamente desde la tienda. Actualiza estados y notifica a tus clientes automáticamente.' },
    { icon:'fa-chart-line',   color:'#0891b2', bg:'rgba(8,145,178,.12)',  title:'Reportes y analytics',   desc:'Visualiza ventas, inventario y productos más vendidos con gráficas interactivas y exportación a CSV o PDF.' },
    { icon:'fa-bell',         color:'#059669', bg:'rgba(5,150,105,.12)',  title:'Notificaciones',          desc:'Alertas internas de stock bajo y pedidos pendientes. Emails automáticos al cliente en cada cambio de estado.' },
    { icon:'fa-users',        color:'#d97706', bg:'rgba(217,119,6,.12)',  title:'Gestión de usuarios',     desc:'Administra los accesos al panel. Importa usuarios desde CSV y filtra por fecha de registro.' },
    { icon:'fa-shield-halved',color:'#6366f1', bg:'rgba(99,102,241,.12)', title:'Panel seguro',            desc:'Autenticación protegida, modo oscuro, diseño responsivo y accesibilidad integrada en todo el sistema.' },
  ];

  const stats = [
    { value:'100%', label:'Responsivo', icon:'fa-mobile-screen-button' },
    { value:'3',    label:'Reportes',   icon:'fa-chart-bar' },
    { value:'∞',    label:'Productos',  icon:'fa-box' },
    { value:'24/7', label:'Disponible', icon:'fa-clock' },
  ];

  return (
    <section id="sistema" className="py-24 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Header */}
        <div className="text-center mb-16">
          <span className="inline-block text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4"
            style={{background:'rgba(59,89,255,.08)',color:'#3B59FF',border:'1px solid rgba(59,89,255,.15)'}}>
            Sistema de gestión
          </span>
          <h2 className="text-4xl sm:text-5xl font-black text-gray-900 leading-tight">
            Todo lo que necesitas<br/>
            <span style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE)',WebkitBackgroundClip:'text',WebkitTextFillColor:'transparent'}}>
              en un solo lugar
            </span>
          </h2>
          <p className="text-gray-500 mt-4 max-w-xl mx-auto text-base leading-relaxed">
            FiftyOne incluye un panel de administración completo para gestionar tu tienda de ropa oversize sin complicaciones.
          </p>
        </div>

        {/* Stats */}
        <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-16">
          {stats.map(s => (
            <div key={s.label} className="text-center p-6 rounded-2xl border border-gray-100 bg-gray-50">
              <i className={`fa-solid ${s.icon} text-xl mb-3 block`} style={{background:'linear-gradient(135deg,#3B59FF,#7B2FBE)',WebkitBackgroundClip:'text',WebkitTextFillColor:'transparent'}}></i>
              <p className="text-3xl font-black text-gray-900">{s.value}</p>
              <p className="text-xs text-gray-400 font-semibold uppercase tracking-wide mt-1">{s.label}</p>
            </div>
          ))}
        </div>

        {/* Features grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
          {features.map(f => (
            <div key={f.title} className="group p-6 rounded-2xl border border-gray-100 hover:border-indigo-200 hover:shadow-lg transition-all duration-300 bg-white">
              <div className="w-12 h-12 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110 duration-300"
                style={{background:f.bg}}>
                <i className={`fa-solid ${f.icon} text-base`} style={{color:f.color}}></i>
              </div>
              <h3 className="font-bold text-gray-900 mb-2">{f.title}</h3>
              <p className="text-sm text-gray-500 leading-relaxed">{f.desc}</p>
            </div>
          ))}
        </div>

        {/* CTA */}
        <div className="relative rounded-3xl overflow-hidden p-10 sm:p-14 text-center"
          style={{background:'linear-gradient(135deg,#0d0d1a 0%,#0a0e2e 55%,#1a0a2e 100%)'}}>
          <div className="absolute inset-0 opacity-20"
            style={{backgroundImage:'radial-gradient(circle at 20% 50%,#3B59FF 0%,transparent 50%),radial-gradient(circle at 80% 50%,#7B2FBE 0%,transparent 50%)'}}></div>
          <div className="relative z-10">
            <h3 className="text-3xl sm:text-4xl font-black text-white mb-3">¿Listo para gestionar tu tienda?</h3>
            <p className="text-gray-400 mb-8 max-w-md mx-auto">Accede al panel de administración y toma el control de tu negocio.</p>
            <div className="flex flex-col sm:flex-row gap-3 justify-center">
              <a href="/admin/dashboard"
                className="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl text-white font-bold text-sm transition-all hover:scale-105 hover:shadow-2xl"
                style={{background:'linear-gradient(90deg,#3B59FF,#7B2FBE)',boxShadow:'0 8px 30px rgba(59,89,255,.4)'}}>
                <i className="fa-solid fa-gauge-high"></i> Ir al panel admin
              </a>
              <a href="/catalogo"
                className="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl font-bold text-sm transition-all hover:bg-white/20"
                style={{background:'rgba(255,255,255,.08)',color:'white',border:'1px solid rgba(255,255,255,.15)'}}>
                <i className="fa-solid fa-shirt"></i> Ver catálogo
              </a>
            </div>
          </div>
        </div>

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
            { title:'Tienda',  links:['Hoodies','Camisetas','Pantalones','Accesorios'] },
            { title:'Empresa', links:['Sobre nosotros','Blog','Trabaja con nosotros','Prensa'] },
            { title:'Soporte', links:['Contacto','Envíos','Devoluciones','FAQ'] },
          ].map(col => (
            <div key={col.title}>
              <h4 className="text-white font-semibold text-sm mb-4">{col.title}</h4>
              <ul className="space-y-2.5">
                {col.links.map(l => <li key={l}><a href="#" className="text-sm text-gray-400 hover:text-[#3B59FF] transition-colors">{l}</a></li>)}
              </ul>
            </div>
          ))}
        </div>
        <div className="border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
          <p className="text-xs text-gray-600">© 2026 FiftyOne. Todos los derechos reservados.</p>
          <div className="flex items-center gap-4 text-xs text-gray-400">
            <a href="#" className="hover:text-[#3B59FF] transition-colors">Privacidad</a>
            <a href="#" className="hover:text-[#3B59FF] transition-colors">Términos</a>
            <a href="/admin/login" className="hover:text-[#3B59FF] transition-colors">Admin</a>
          </div>
        </div>
      </div>
    </footer>
  );
}

// ── App ────────────────────────────────────────────────────────────────────
function App() {
  const [cart, setCart]           = useState(() => JSON.parse(localStorage.getItem('fiftyone_cart') || '[]'));
  const [drawerOpen, setDrawer]   = useState(false);
  const [showCheckout, setCheckout] = useState(false);
  const [successId, setSuccessId] = useState(null);

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
      <Navbar cartCount={totalItems} onCartOpen={() => setDrawer(true)} />
      <Hero />
      <Categories />
      <Products onAdd={addToCart} />
      <PromoBanner />
      <Testimonials />
      <SystemSection />
      <Footer />
      <CartDrawer open={drawerOpen} onClose={() => setDrawer(false)} cart={cart}
        onUpdateQty={updateQty} onRemove={removeItem} onClear={clearCart} onCheckout={openCheckout} />
      {showCheckout && <CheckoutModal cart={cart} onClose={() => setCheckout(false)} onSuccess={handleSuccess} />}
      {successId && <SuccessModal orderId={successId} onClose={() => setSuccessId(null)} />}
    </>
  );
}

ReactDOM.createRoot(document.getElementById('root')).render(<App />);
</script>
@endverbatim
</body>
</html>
