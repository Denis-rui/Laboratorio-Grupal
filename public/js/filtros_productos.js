const filtrosProductosScript = document.currentScript;
const FILTROS_PRODUCTOS_BASE_URL = filtrosProductosScript?.dataset.baseUrl || '';

document.addEventListener('DOMContentLoaded', function () {
    const selectCategoria = document.getElementById('filtro_categoria');

    if (!selectCategoria) {
        return;
    }

    selectCategoria.addEventListener('change', function () {
        const categoriaId = this.value;

        if (categoriaId === '') {
            limpiarFiltrosProductos('Seleccione una categoría');
            // ── Cuando vuelve a "Todas", disparar búsqueda sin categoría ──
            if (typeof buscarProductos === 'function') {
                buscarProductos();
            }
            return;
        }

        cargarFiltros(categoriaId);
    });
});

async function cargarFiltros(categoriaId) {
    const contenedor = document.getElementById('contenedor-filtros');

    if (!contenedor) {
        return;
    }

    limpiarFiltrosProductos('Cargando filtros...');

    try {
        const url = FILTROS_PRODUCTOS_BASE_URL + 'productos/apiGetFiltros?categoria_id=' + encodeURIComponent(categoriaId);
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error('Error HTTP: ' + response.status);
        }

        const data = await response.json();

        if (!data.success) {
            limpiarFiltrosProductos('No se pudieron cargar los filtros.');
            return;
        }

        actualizarFiltrosProductos(data.filtros || {});

        // ── Disparar búsqueda al cambiar categoría ────────────────
        if (typeof buscarProductos === 'function') {
            buscarProductos();
        }

    } catch (error) {
        console.error('Error al cargar filtros:', error);
        limpiarFiltrosProductos('Error al cargar los filtros.', true);
    }
}

function actualizarFiltrosProductos(filtros) {
    const contenedor = document.getElementById('contenedor-filtros');

    if (!contenedor) {
        return;
    }

    contenedor.replaceChildren();

    const filtrosNormalizados = normalizarFiltrosProductos(filtros);

    if (filtrosNormalizados.length === 0) {
        limpiarFiltrosProductos('Esta categoría no tiene filtros disponibles.');
        return;
    }

    filtrosNormalizados.forEach(function (filtro) {
        contenedor.appendChild(crearSelectFiltroProducto(filtro));
    });

    actualizarEstadoFiltrosProductos(filtrosNormalizados.length + ' filtros disponibles');
}

function normalizarFiltrosProductos(filtros) {
    if (Array.isArray(filtros)) {
        return filtros.map(function (filtro) {
            return {
                atributo: filtro.nombre || filtro.atributo || filtro.nombre_atributo,
                valores: Array.isArray(filtro.valores) ? filtro.valores : [filtro.valor],
            };
        }).filter(function (filtro) {
            return filtro.atributo && filtro.valores.length > 0;
        });
    }

    return Object.keys(filtros).map(function (atributo) {
        return {
            atributo: atributo,
            valores: Array.isArray(filtros[atributo]) ? filtros[atributo] : [],
        };
    }).filter(function (filtro) {
        return filtro.valores.length > 0;
    });
}

function crearSelectFiltroProducto(filtro) {
    const grupo = document.createElement('div');
    grupo.className = 'productos-filtros__grupo';

    const label = document.createElement('label');
    label.textContent = filtro.atributo;

    const select = document.createElement('select');
    const atributoKey = filtro.atributo.toLowerCase().replace(/\s+/g, '_');
    select.name = 'filtro_' + atributoKey;
    select.id = 'filtro_' + atributoKey;

    label.setAttribute('for', select.id);

    const opcionBase = document.createElement('option');
    opcionBase.value = '';
    opcionBase.textContent = 'Todos';
    select.appendChild(opcionBase);

    filtro.valores.forEach(function (valor) {
        const option = document.createElement('option');
        option.value = String(valor);
        option.textContent = String(valor);
        select.appendChild(option);
    });

    grupo.append(label, select);
    return grupo;
}

function limpiarFiltrosProductos(mensaje, esError) {
    const contenedor = document.getElementById('contenedor-filtros');

    if (contenedor) {
        contenedor.replaceChildren();
    }

    actualizarEstadoFiltrosProductos(mensaje, esError);
}

function actualizarEstadoFiltrosProductos(mensaje, esError) {
    const estado = document.getElementById('estado-filtros-productos');

    if (!estado) {
        return;
    }

    estado.textContent = mensaje;
    estado.classList.toggle('productos-filtros__estado--error', Boolean(esError));
}

document.addEventListener('productos:filtros-recibidos', function (event) {
    actualizarFiltrosProductos(event.detail || {});
});

window.cargarFiltros = cargarFiltros;
window.actualizarFiltrosProductos = actualizarFiltrosProductos;
