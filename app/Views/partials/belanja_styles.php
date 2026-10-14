<style>
.belanja-page{--bp-border:#e2e8f0;--bp-muted:#64748b;--bp-blue:#0d6efd;color:#0f172a;padding:24px clamp(12px,2vw,40px) 40px;width:100%;box-sizing:border-box}
.belanja-page *{box-sizing:border-box}.belanja-page a{text-decoration:none}.belanja-page .bp-muted{color:var(--bp-muted)}
.belanja-page .bp-heading{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px}
.belanja-page h1{font-size:clamp(1.65rem,2.2vw,2.25rem);font-weight:800;margin:0 0 6px}.belanja-page .bp-heading p{margin:0;color:var(--bp-muted)}
.belanja-page .bp-eyebrow{color:var(--bp-blue);font-size:.78rem;font-weight:700;letter-spacing:.09em;text-transform:uppercase;margin-bottom:8px}
.belanja-page .bp-layout{display:grid;grid-template-columns:minmax(0,1fr) 340px;gap:20px;align-items:start}
.belanja-page .bp-panel{background:#fff;border:1px solid var(--bp-border);border-radius:20px;box-shadow:0 6px 20px rgba(15,23,42,.04);overflow:hidden;min-width:0}
.belanja-page .bp-panel-head{padding:18px 20px;display:flex;gap:12px;align-items:center;justify-content:space-between;border-bottom:1px solid var(--bp-border);flex-wrap:wrap}
.belanja-page .bp-panel-head h2{font-size:1rem;font-weight:700;margin:0}.belanja-page form{margin:0}
.belanja-page .bp-item{display:grid;grid-template-columns:80px minmax(0,1fr) 164px 160px;gap:18px;align-items:center;padding:22px 20px;border-bottom:1px solid var(--bp-border)}
.belanja-page .bp-item:last-child{border-bottom:0}.belanja-page .bp-thumb{width:80px;height:80px;display:flex;align-items:center;justify-content:center;border-radius:14px;background:#eff6ff;color:#0d6efd;font-size:1.8rem;overflow:hidden}
.belanja-page .bp-thumb img{width:100%;height:100%;object-fit:cover}.belanja-page .bp-name{font-size:1rem;font-weight:700;line-height:1.45;margin:5px 0;color:#0f172a;overflow-wrap:anywhere}
.belanja-page .bp-name a{color:inherit}.belanja-page .bp-name a:hover{color:var(--bp-blue)}.belanja-page .bp-category{font-size:.75rem;color:var(--bp-muted);overflow-wrap:anywhere}
.belanja-page .bp-stock{font-size:.78rem;color:#15803d;margin-top:6px}.belanja-page .bp-stock-warning{color:#b45309}.belanja-page .bp-price{font-weight:700;color:#0d6efd;overflow-wrap:anywhere}
.belanja-page .bp-qty-label{font-size:.75rem;color:var(--bp-muted);display:block;margin-bottom:7px}.belanja-page .bp-qty{display:flex;border:1px solid var(--bp-border);border-radius:10px;overflow:hidden;background:#f8fafc}
.belanja-page .bp-qty button{width:36px;min-height:40px;border:0;background:transparent;color:#334155;flex-shrink:0}.belanja-page .bp-qty button:hover{background:#e2e8f0}
.belanja-page .bp-qty input{width:100%;min-width:0;border:0;background:#fff;text-align:center;font-weight:600;appearance:textfield;-moz-appearance:textfield}
.belanja-page .bp-qty input::-webkit-inner-spin-button{appearance:none}.belanja-page .bp-qty input:focus{outline:2px solid #0d6efd;outline-offset:-2px}
.belanja-page .bp-update{margin-top:8px;width:100%;font-size:.8rem}.belanja-page .bp-subtotal{text-align:right}.belanja-page .bp-subtotal small{display:block;color:var(--bp-muted);margin-bottom:4px}
.belanja-page .bp-remove{padding:5px 0;border:0;color:#dc3545;background:transparent;font-size:.8rem;margin-top:8px}.belanja-page .bp-remove:hover{text-decoration:underline}
.belanja-page .bp-summary{padding:24px}.belanja-page .bp-summary h2{font-size:1.2rem;font-weight:800;margin:0 0 20px}.belanja-page .bp-summary-line{display:flex;justify-content:space-between;gap:16px;margin:14px 0;font-size:.9rem}
.belanja-page .bp-summary-total{border-top:1px solid var(--bp-border);padding-top:18px;margin-top:18px;display:flex;flex-wrap:wrap;gap:8px;justify-content:space-between;font-weight:700}
.belanja-page .bp-summary-total strong{color:var(--bp-blue);font-size:1.35rem;overflow-wrap:anywhere}.belanja-page .bp-note{font-size:.8rem;line-height:1.6;color:var(--bp-muted);margin:16px 0 0}
.belanja-page .bp-empty{text-align:center;padding:64px 20px}.belanja-page .bp-empty-icon{width:88px;height:88px;display:grid;place-items:center;background:#eff6ff;border-radius:50%;color:#0d6efd;font-size:2rem;margin:0 auto 20px}
.belanja-page .bp-empty h2{font-size:1.5rem;font-weight:800}.belanja-page .bp-empty p{color:var(--bp-muted);margin:12px auto 24px;max-width:460px}
.belanja-page .bp-wishlist-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px}.belanja-page .bp-wishlist-card{display:flex;flex-direction:column}
.belanja-page .bp-wishlist-thumb{width:100%;height:200px;border-radius:0;font-size:3rem}.belanja-page .bp-wishlist-body{padding:18px;display:flex;flex:1;flex-direction:column;gap:8px;min-width:0}
.belanja-page .bp-wishlist-actions{margin-top:auto;padding-top:12px;display:grid;gap:8px}.belanja-page .bp-wishlist-actions .btn{width:100%}.belanja-page .bp-wishlist-actions form:last-child{text-align:center}
.belanja-page .btn{white-space:normal}.belanja-page .bp-flash{overflow-wrap:anywhere}.belanja-page .bp-actions{display:flex;gap:10px;flex-wrap:wrap}
@media(min-width:1600px){.belanja-page .bp-wishlist-grid{grid-template-columns:repeat(5,minmax(0,1fr))}}
@media(max-width:1199.98px){.belanja-page .bp-layout{grid-template-columns:minmax(0,1fr) 300px}.belanja-page .bp-item{grid-template-columns:64px minmax(0,1fr);gap:14px}.belanja-page .bp-item .bp-thumb{width:64px;height:64px}.belanja-page .bp-quantity-form{grid-column:2;max-width:180px}.belanja-page .bp-subtotal{grid-column:2;text-align:left}.belanja-page .bp-wishlist-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media(max-width:991.98px){.belanja-page .bp-layout{grid-template-columns:minmax(0,1fr)}.belanja-page .bp-wishlist-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:575.98px){.belanja-page{padding-top:18px}.belanja-page .bp-heading{margin-bottom:18px}.belanja-page .bp-panel-head{padding:16px}.belanja-page .bp-item{padding:18px 14px;gap:12px}.belanja-page .bp-summary{padding:20px}.belanja-page .bp-wishlist-grid{gap:12px}.belanja-page .bp-wishlist-thumb{height:145px;font-size:2rem}.belanja-page .bp-wishlist-body{padding:12px}.belanja-page .bp-wishlist-body .bp-name{font-size:.92rem}.belanja-page .bp-wishlist-actions .btn{font-size:.8rem;padding:9px 6px}}
@media(max-width:359.98px){.belanja-page .bp-wishlist-grid{grid-template-columns:1fr}}
</style>
