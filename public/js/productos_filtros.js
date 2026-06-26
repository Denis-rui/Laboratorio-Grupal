(function () {
    const categoriaSelect = document.querySelector('[data-productos-categoria]');
    const filtrosContainer = document.querySelector('[data-productos-filtros]');
    const estadoFiltros = document.getElementById('estado-filtros-productos');

    if (!categoriaSelect || !filtrosContainer) {
        return;
    }

    const filtrosDemo = {
        Abarrotes: [
            { id: 1, nombre: 'Marca', valores: ['Costeño', 'Generica'] },
            { id: 7, nombre: 'Presentacion', valores: ['Bolsa'] },
            { id: 8, nombre: 'Peso', valores: ['1 kg'] },
            { id: 11, nombre: 'Tipo', valores: ['Arroz', 'Azucar'] },
        ],
        'Lácteos': [
            { id: 1, nombre: 'Marca', valores: ['Gloria', 'Local'] },
            { id: 7, nombre: 'Presentacion', valores: ['Botella', 'Paquete', 'Unidad'] },
            { id: 8, nombre: 'Peso', valores: ['500 g', '1 kg'] },
            { id: 9, nombre: 'Contenido', valores: ['1 L'] },
            { id: 10, nombre: 'Fecha de vencimiento', valores: ['2026-09-30', '2026-10-15', '2026-12-31'] },
        ],
        Limpieza: [
            { id: 1, nombre: 'Marca', valores: ['Bolivar', 'Marsella'] },
            { id: 7, nombre: 'Presentacion', valores: ['Barra', 'Bolsa'] },
            { id: 8, nombre: 'Peso', valores: ['200 g', '800 g'] },
            { id: 12, nombre: 'Aroma', valores: ['Clasico', 'Limon'] },
            { id: 13, nombre: 'Uso', valores: ['Ropa'] },
        ],
        'bebidas alcoholicas': [
            { id: 1, nombre: 'Marca', valores: ['Marca local'] },
            { id: 2, nombre: 'Color', valores: ['Dorada'] },
            { id: 7, nombre: 'Presentacion', valores: ['Botella'] },
            { id: 9, nombre: 'Contenido', valores: ['620 ml'] },
            { id: 14, nombre: 'Grado alcoholico', valores: ['5%'] },
        ],
        'Ropa de Hombre': [
            { id: 1, nombre: 'Marca', valores: ['Lacoste'] },
            { id: 2, nombre: 'Color', valores: ['Rojo'] },
            { id: 3, nombre: 'Talla', valores: ['L'] },
        ],
        Telefonos: [
            { id: 1, nombre: 'Marca', valores: ['Samsung'] },
            { id: 2, nombre: 'Color', valores: ['Azul'] },
            { id: 4, nombre: 'Año', valores: ['2025'] },
            { id: 5, nombre: 'Sist. Operativo', valores: ['Android 15'] },
            { id: 6, nombre: 'Almacenamiento', valores: ['256GB'] },
        ],
    };

    function limpiarFiltros(mensaje) {
        filtrosContainer.replaceChildren();
        actualizarEstado(mensaje || 'Seleccione una categoría');
    }

    function actualizarEstado(mensaje) {
        if (estadoFiltros) {
            estadoFiltros.textContent = mensaje;
        }
    }

    function normalizarFiltros(payload) {
        const datos = Array.isArray(payload) ? payload : (payload && payload.atributos) || [];
        const filtrosPorId = new Map();

        datos.forEach(function (item) {
            const atributoId = item.id || item.atributo_id || item.id_atributo || item.nombre;
            const nombre = item.nombre || item.atributo || item.nombre_atributo;

            if (!atributoId || !nombre) {
                return;
            }

            if (!filtrosPorId.has(atributoId)) {
                filtrosPorId.set(atributoId, {
                    id: atributoId,
                    nombre: nombre,
                    valores: [],
                });
            }

            const filtro = filtrosPorId.get(atributoId);
            const valores = Array.isArray(item.valores) ? item.valores : [item.valor];

            valores.forEach(function (valor) {
                if (valor === undefined || valor === null || valor === '') {
                    return;
                }

                const valorTexto = String(valor);
                if (!filtro.valores.includes(valorTexto)) {
                    filtro.valores.push(valorTexto);
                }
            });
        });

        return Array.from(filtrosPorId.values());
    }

    function crearFiltroSelect(filtro) {
        const grupo = document.createElement('div');
        grupo.className = 'productos-filtros__grupo';

        const label = document.createElement('label');
        label.textContent = filtro.nombre;
        label.setAttribute('for', 'filtro_atributo_' + filtro.id);

        const select = document.createElement('select');
        select.id = 'filtro_atributo_' + filtro.id;
        select.name = 'atributos[' + filtro.id + ']';
        select.dataset.atributoId = filtro.id;

        const opcionBase = document.createElement('option');
        opcionBase.value = '';
        opcionBase.textContent = 'Todos';
        select.appendChild(opcionBase);

        filtro.valores.forEach(function (valor) {
            const option = document.createElement('option');
            option.value = valor;
            option.textContent = valor;
            select.appendChild(option);
        });

        grupo.append(label, select);
        return grupo;
    }

    function actualizarFiltrosProductos(payload) {
        const filtros = normalizarFiltros(payload);
        filtrosContainer.replaceChildren();

        if (filtros.length === 0) {
            limpiarFiltros('Sin filtros para esta categoría');
            return;
        }

        filtros.forEach(function (filtro) {
            filtrosContainer.appendChild(crearFiltroSelect(filtro));
        });

        actualizarEstado(filtros.length + ' filtros disponibles');
    }

    categoriaSelect.addEventListener('change', function () {
        const categoriaId = categoriaSelect.value;
        const categoriaNombre = categoriaSelect.options[categoriaSelect.selectedIndex].textContent.trim();

        if (!categoriaId) {
            limpiarFiltros('Seleccione una categoría');
            return;
        }

        limpiarFiltros('Cargando filtros...');

        document.dispatchEvent(new CustomEvent('productos:categoria-cambiada', {
            detail: {
                categoriaId: categoriaId,
                categoriaNombre: categoriaNombre,
                actualizarFiltros: actualizarFiltrosProductos,
            },
        }));

        if (filtrosDemo[categoriaNombre]) {
            actualizarFiltrosProductos(filtrosDemo[categoriaNombre]);
        }
    });

    document.addEventListener('productos:filtros-recibidos', function (event) {
        actualizarFiltrosProductos(event.detail || []);
    });

    window.actualizarFiltrosProductos = actualizarFiltrosProductos;
})();
