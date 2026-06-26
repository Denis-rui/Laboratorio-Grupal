document.addEventListener("DOMContentLoaded", function () {
    const selectCategoria = document.getElementById("filtro_categoria");

    if (selectCategoria) {
        selectCategoria.addEventListener("change", function () {
            const categoriaId = this.value;

            if (categoriaId === "") {
                // Si no eligió categoría, limpia los filtros
                document.getElementById("contenedor-filtros").innerHTML = "";
                return;
            }

            cargarFiltros(categoriaId);
        });
    }
});

async function cargarFiltros(categoriaId) {
    const contenedor = document.getElementById("contenedor-filtros");
    contenedor.innerHTML = "<p>Cargando filtros...</p>";

    try {
        // Llama al endpoint del controlador
        const response = await fetch(BASE_URL + "productos/apiGetFiltros?categoria_id=" + categoriaId);

        if (!response.ok) {
            throw new Error("Error HTTP: " + response.status);
        }

        const data = await response.json();

        if (!data.success) {
            contenedor.innerHTML = "<p>No se pudieron cargar los filtros.</p>";
            return;
        }

        // Construye los <select> dinámicamente
        let html = "";
        const filtros = data.filtros;

        // Si no hay atributos para esa categoría
        if (Object.keys(filtros).length === 0) {
            contenedor.innerHTML = "<p>Esta categoría no tiene filtros disponibles.</p>";
            return;
        }

        for (const atributo in filtros) {
            const valores = filtros[atributo];

            html += `
                <div style="display: inline-block; margin-right: 20px; margin-top: 8px;">
                    <label><strong>${atributo}:</strong></label><br>
                    <select name="filtro_${atributo.toLowerCase()}">
                        <option value="">Todos</option>
                        ${valores.map(v => `<option value="${v}">${v}</option>`).join("")}
                    </select>
                </div>
            `;
        }

        contenedor.innerHTML = html;

    } catch (error) {
        console.error("Error al cargar filtros:", error);
        contenedor.innerHTML = "<p style='color:red;'>Error al cargar los filtros.</p>";
    }
}