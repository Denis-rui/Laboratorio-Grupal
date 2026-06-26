const busquedaScript = document.currentScript;
const BUSQUEDA_BASE_URL = busquedaScript?.dataset.baseUrl || '';

const inputBuscar = document.getElementById('inputBuscar');
const tbody       = document.getElementById('tablaProductos_body');

let debounceTimer = null;

// ── EVENTO INPUT con DEBOUNCE 400ms ───────────────────────────────────
inputBuscar.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(buscarProductos, 400);
});

// ── Escuchar cambios en los filtros dinámicos ─────────────────────────
document.getElementById('contenedor-filtros').addEventListener('change', () => {
    
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(buscarProductos, 400);
});

// ── BUSCAR con Fetch + async/await ────────────────────────────────────
async function buscarProductos() {
    const busqueda    = inputBuscar.value.trim();
    const categoriaEl = document.getElementById('filtro_categoria');
    const catId       = categoriaEl ? categoriaEl.value : '';

    const params = new URLSearchParams();
    params.set('q', busqueda);
    params.set('categoria_id', catId);

    // Recoger filtros dinámicos activos
    document.querySelectorAll('[id^="filtro_"]:not(#filtro_categoria)').forEach(sel => {
        if (sel.value !== '') {
            params.append('filtros[]', sel.value);
        }
    });

    try {
        const res  = await fetch(`${BUSQUEDA_BASE_URL}productos/apiBuscar?${params.toString()}`);
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json();
        renderTabla(data);
    } catch (err) {
        console.error('Error en búsqueda:', err);
    }
}

// ── RENDER con textContent (previene XSS) ─────────────────────────────
function renderTabla(productos) {
    tbody.innerHTML = '';

    if (productos.length === 0) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan     = 7;
        td.textContent = 'No se encontraron productos.';
        tr.appendChild(td);
        tbody.appendChild(tr);
        return;
    }

    productos.forEach(p => {
        const tr = document.createElement('tr');

        [p.id, p.nombre, p.categoria,
         parseFloat(p.precio).toFixed(2),
         p.stock,
         parseInt(p.estado) === 1 ? 'Activo' : 'Inactivo'
        ].forEach(val => {
            const td = document.createElement('td');
            td.textContent = val;
            tr.appendChild(td);
        });

        // Acciones
        const tdAcc = document.createElement('td');
        const aVer  = document.createElement('a');
        aVer.href        = `${BUSQUEDA_BASE_URL}productos/ver/${p.id}`;
        aVer.textContent = 'Ver';
        const aEdit = document.createElement('a');
        aEdit.href        = `${BUSQUEDA_BASE_URL}productos/editar/${p.id}`;
        aEdit.textContent = ' Editar';
        tdAcc.appendChild(aVer);
        tdAcc.appendChild(aEdit);
        tr.appendChild(tdAcc);
        tbody.appendChild(tr);
    });
}