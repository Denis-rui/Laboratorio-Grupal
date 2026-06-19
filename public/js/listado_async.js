const tblUsuarios_body = document.getElementById("tblUsuario_body");
const script = document.currentScript || document.getElementById("listado-usuarios-script");
const baseUrl = script.dataset.baseUrl;

document.addEventListener("DOMContentLoaded", function () {
  cargarUsuarios();
});

async function cargarUsuarios() {
  try {
    const response = await fetch(baseUrl + "usuarios/cargarUsuariosAsincronico");

    if (!response.ok) {
      throw new Error("HTTP " + response.status);
    }

    const data = await response.json();
    var html = "";

    data.forEach((usuario) => {
      html += `
              <tr>
                  <td>${usuario.id}</td>
                  <td>${usuario.nombre}</td>
                  <td>${usuario.correo}</td>
                  <td>${usuario.clave.substring(0, 25)}...</td>
                  <td>${usuario.estado}</td>
              </tr>
              `;
    });

    tblUsuarios_body.innerHTML = html || '<tr><td colspan="5">No hay usuarios activos.</td></tr>';
  } catch (error) {
    console.error("No se pudieron cargar los usuarios:", error);
    tblUsuarios_body.innerHTML = '<tr><td colspan="5">No se pudieron cargar los usuarios.</td></tr>';
  }
}
