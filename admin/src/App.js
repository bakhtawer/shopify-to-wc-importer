import React, { useState } from 'react';

function App(){
  const [loading, setLoading] = useState(false);
  const [log, setLog] = useState(null);

  async function startImport(){
    setLoading(true);
    const res = await fetch(STWI_Settings.rest_url, {
      method: 'POST',
      headers: {
        'X-WP-Nonce': STWI_Settings.nonce,
        'Content-Type': 'application/json'
      },
      credentials: 'same-origin'
    });
    const json = await res.json();
    setLog(json);
    setLoading(false);
  }

  return (
    <div style={{padding:20,fontFamily:'Arial,Helvetica,sans-serif'}}>
      <h1>Shopify → WooCommerce Importer</h1>
      <p>Configure credentials in <a href="admin.php?page=stwi-settings">Settings</a>.</p>
      <button onClick={startImport} disabled={loading} className="stwi-btn">
        {loading ? 'Importing...' : 'Start Import'}
      </button>
      <div className="stwi-box" style={{marginTop:12}}>
        <pre>{log ? JSON.stringify(log, null, 2) : 'No import run yet.'}</pre>
      </div>
    </div>
  );
}

export default App;
