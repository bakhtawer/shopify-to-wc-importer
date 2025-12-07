(function(){
    // Simple admin UI (vanilla JS) that behaves like the React app
    function el(tag, attrs, children){
        var e = document.createElement(tag);
        attrs = attrs || {};
        for (var k in attrs){
            if (k === 'class') e.className = attrs[k];
            else if (k === 'html') e.innerHTML = attrs[k];
            else e.setAttribute(k, attrs[k]);
        }
        (children||[]).forEach(function(c){ 
            if (typeof c === 'string') e.appendChild(document.createTextNode(c));
            else e.appendChild(c);
        });
        return e;
    }
    function render(){
        var root = document.getElementById('stwi-root');
        if (!root) return;
        root.innerHTML = '';
        var title = el('h1', {}, ['Shopify → WooCommerce Importer']);
        var link = el('p', {}, ['Configure your Shopify credentials at the plugin Settings: ', el('a',{href: 'admin.php?page=stwi-settings'},['Settings'])]);
        var btn = el('button', {class:'stwi-btn'}, ['Start Import']);
        var out = el('div', {class:'stwi-box', id:'stwi-output'}, []);
        btn.addEventListener('click', function(){
            btn.disabled = true;
            btn.innerText = 'Importing...';
            out.innerHTML = '<em>Starting import…</em>';
            fetch(STWI_Settings.rest_url, {
                method:'POST',
                headers:{
                    'X-WP-Nonce': STWI_Settings.nonce,
                    'Content-Type':'application/json'
                },
                credentials:'same-origin'
            }).then(function(r){ return r.json(); })
            .then(function(json){
                out.innerHTML = '<pre>' + JSON.stringify(json, null, 2) + '</pre>';
            })
            .catch(function(err){
                out.innerHTML = '<div style="color:#a00">Error: '+ (err.message || err) +'</div>';
            })
            .finally(function(){ btn.disabled = false; btn.innerText = 'Start Import'; });
        });
        root.appendChild(title);
        root.appendChild(link);
        root.appendChild(btn);
        root.appendChild(out);
    }
    document.addEventListener('DOMContentLoaded', render);
    if (document.readyState === 'complete' || document.readyState === 'interactive') render();
})();
