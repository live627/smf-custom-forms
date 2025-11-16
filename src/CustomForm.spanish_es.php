<?php

declare(strict_types=1);

/**
 * @package   Custom Form mod
 * @version   4.0.5
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

global $helptxt;

//	Header and General text for the Custom Form Mod settings area.
$txt['customform_generalsettings_heading'] = 'Configuración del mod formulario personalizado';
$txt['customform_tabheader'] = 'Formulario Personalizado';
$txt['customform_update_available'] = '¡Actualización disponible!';
$txt['customform_update_action'] = 'Actualizado a %s';
$txt['customform_update_later'] = 'Recuérdamelo más tarde';
$txt['customform_form'] = 'Formulario';
$txt['customform_field'] = 'Campo';
$txt['customform_current_version'] = 'Current version: {version}';
$txt['customform_view_title'] = 'Ver título del formulario';
$txt['customform_view_title_desc'] = 'Elija el título de la acción/página que muestra la lista de formularios a un usuario (en "index.php?action=form"). Valores predeterminados para <b>Formularios Personalizados</b>.';
$txt['customform_view_text'] = 'Ver texto de formulario';
$txt['customform_view_text_desc'] = 'Elija el texto de explicación para la acción/página que muestra la lista de formularios a un usuario (en "index.php?action=form").';
$txt['customform_view_perms'] = 'Permisos de página para visualización de formulario(s)';
$txt['customform_view_perms_desc'] = 'Limit the member groups that can see the form list. Individual forms and do not inherit from this.';

//	Stuff for the forms action
$txt['customform_submit'] = 'Enviar formulario';
$txt['customform_required'] = 'Los campos marcados con un asterisco (*) son obligatorios';

//	Junk for the list areas.
$txt['customform_listheading_fields'] = 'Campos de formularios';
$txt['customform_add_form'] = 'Agregar nuevo formulario';
$txt['customform_add_field'] = 'Agregar nuevo campo';
$txt['customform_edit'] = 'Editar';
$txt['customform_delete_warning'] = '¿Estás seguro de que quieres eliminar esto?';
$txt['customform_list_noelements'] = 'Esta lista está actualmente vacía.';
$txt['customform_moveup'] = 'Subir';
$txt['customform_movedown'] = 'Bajar';

//	Text for the settings pages.
$txt['customform_subject'] = 'Asunto del formulario';
$txt['customform_subject_desc'] = 'Asunto del mensaje final. Admite macros para nombres de campo.';
$txt['customform_output_type'] = 'Form Output Method';
$txt['customform_output_type_desc'] = 'Choose how form output is sent.';
$txt['custom_form_output_forum_post'] = 'Forum Post';
$txt['customform_output'] = 'Salida de formulario';
$txt['customform_output_desc'] = 'Enter the identifier of the field between a set of braces <code>{{ name }}</code> to view the data entered in the form.';
$txt['customform_board'] = 'Foro en el que publicar';
$txt['customform_board_desc'] = 'Choose the board where the form will post to. The target board can be hidden.';
$txt['customform_template_function'] = 'Función de plantilla personalizada';
$txt['customform_exit'] = 'Enviar redirección';

//  Redirection Text
$txt['customform_redirect_forum'] = 'Foro';
$txt['customform_redirect_topic'] = 'Tema';
$txt['customform_redirect_board'] = 'Foros (por defecto)';
$txt['customform_redirect_thanks'] = 'Gracias';
$txt['customform_redirect_list'] = 'Lista de formularios';

//  Text for the thankyou page
$txt['customform_thankyou'] = '
	<p>La información que ingresó ha sido enviada.</p>
	<p>Gracias por tomarse el tiempo para completar este formulario.</p>
	<p>Ahora puede regresar al foro o ver la lista de formularios, si está disponible.</p>';

//	Options for the field edit page.
$txt['customform_character_warning'] = 'El identificador de este campo contiene algunos caracteres no permitidos.';
$txt['customform_current_identifier'] = 'Tu identificador es %s';
$txt['customform_suggested_identifier'] = 'El identificador sugerido es %s';

$txt['customform_identifier'] = 'Identificador';
$txt['customform_text'] = 'Descripción';
$txt['customform_text_desc'] = 'Una descripción del campo, que se muestra al usuario cuando ingresa la información.';
$txt['customform_type'] = 'Tipo';
$txt['customform_type_vars'] = 'Parámetros de tipo adicionales';
$txt['custom_form_fields_text'] = 'Text';
$txt['custom_form_fields_textarea'] = 'Large Text';
$txt['custom_form_fields_select'] = 'Select Box';
$txt['custom_form_fields_radio'] = 'Radio Buttons';
$txt['custom_form_fields_check'] = 'Checkbox';
$txt['custom_form_fields_info'] = 'Information';
$txt['customform_max_length'] = 'Longitud máxima';
$txt['customform_max_length_desc'] = '(0 para sin límite)';
$txt['customform_dimension'] = 'Dimensiones';
$txt['customform_dimension_row'] = 'Filas';
$txt['customform_dimension_col'] = 'Columnas';
$txt['customform_size'] = 'Número máximo de caracteres';
$txt['customform_bbc'] = 'Permitir BBC';
$txt['customform_options'] = 'Opciones';
$txt['customform_options_desc'] = 'Deje el cuadro de opción en blanco para eliminarlo. El botón de radio selecciona la opción por defecto.';
$txt['customform_options_more'] = 'Más';
$txt['customform_default'] = 'Estado por defecto';
$txt['customform_active'] = 'Activo';
$txt['customform_active_desc'] = 'Este campo se desactivará si no se marca.';
$txt['customform_mask'] = 'Máscara de entrada';
$txt['customform_mask_desc'] = 'Esto valida la entrada proporcionada por el usuario.';
$txt['customform_mask_number'] = 'Número entero (no se permite la notación científica)';
$txt['customform_mask_float'] = 'Entero de coma flotante (decimales permitidos)';
$txt['customform_mask_email'] = 'Correo electrónico (debe tener menos de 255 caracteres)';
$txt['customform_mask_regex'] = 'Expresión regular (¡Solo para expertos!)';
$txt['customform_regex'] = 'Expresión regular';
$txt['customform_regex_desc'] = 'Valida tu propio camino.';

// Validation errors
$txt['customform_error_title'] = '¡Ups, hubo errores!';
$txt['customform_invalid_value'] = 'El valor que eligió para %1$s no es válido.';

// argument(s): $scripturl
$txt['whoallow_form'] = 'Viendo la <a href="%s?action=form">lista de formularios</a>.';
// argument(s): $scripturl, $id_form, $title
$txt['customform_who'] = 'Viendo <a href="%s?action=form;n=%s">%s</a>.';

//	Help text for the edit form page.
$helptxt['customform_output'] = '
	<p>Este es el formato en el que los datos que ingresan los usuarios en el formulario se mostrarán en la publicación del foro después de enviar el formulario.</p>
	<p>Para mostrar realmente los datos que un usuario ingresa en la publicación del foro, deberá ingresar el título del campo entre llaves { }.</p>
	<p><b>Ejemplo</b>:i el campo se llama \'name\' entonces <code>{{ name }}</code> sería reemplazado por lo que el usuario ingresó en el campo del formulario.</p>
	<p><code class="tfacode">Mi nombre es {{ name }}.</code></p>
	<p>Luego, si el usuario ingresara "Juan" en el campo de formulario correspondiente, la publicación del foro mostraría "Mi nombre es Juan".</p>
	';
$helptxt['customform_submit_exit'] = '
	<p>This setting allows you to specify where the user goes after completing the form.</p>
	<p>You can use the macros listed below.</p>
	<ul class="normallist">
		<li>
			<b>board</b>
			&nbsp;- redirects the user to the board where the form posts (this is also the default if you leave the field empty).
		</li>
		<li><b>forum</b>
			&nbsp;- redirects the user to the Forum Index page.
		</li>
		<li><b>form</b>
			&nbsp;- redirects the user to the list of available forms to fill out, if they have permission to view it.
		</li>
		<li><b>thanks</b>
			&nbsp;- redirects the user to a simple page that informs them that they correctly completed the form and thanks them for doing so.
		</li>
	</ul>
	<p>The keywords "forum," "form," and "thanks" are helpful if the form posts to a board that the user is unable to access.</p>
	<p>You can also enter a URL like <code>https://www.eample.com/</code> and the user will be directed to that URL. This can be useful to redirect users to a custom thank you page, another specific form, a specific forum post, or anyplace else on the internet.</p>
	';
$helptxt['customform_template_function'] = '
	<p>Las funciones de plantillas personalizadas se pueden agregar a <code>./themes/default/CustomFormUserland.template.php</code>. Tenga en cuenta que la función de plantilla que se utilizará debe nombrarse con el formato <code>template_{value for this setting}</code>, de lo contrario, la función de plantilla predeterminada <code>template_form_my_custom_template()</code> será usada.</p>
	<p>Luego puede usar la documentación de esa función para ver cómo el Mod le pasa la información, lo que le permite cambiarla para sus propósitos.</p>
	<p>Para obtener un breve ejemplo de lo que puede cambiar en una plantilla, ingrese "ejemplo" en la Función de plantilla personalizada de un formulario y luego vea ese formulario. Verá varios lugares con el texto <span style="color:red">"Ejemplo de algo"</span>, estos son buenos lugares para agregar información a su plantilla de formulario sin afectar las funciones del formulario en sí.</p>
	<p class="infobox"><b><span style="color:red">Importante</span></b>: Debe tener conocimientos básicos de trabajo de HTML y PHP antes de hacer algo demasiado drástico.</p>
	<p class="noticebox">¡La codificación incorrecta hará que el Mod de formulario personalizado y posiblemente su foro se rompa! Por lo tanto, haga una copia de seguridad de su formulario o al menos <code>./themes/default/CustomFormUserland.template.php</code> antes de realizar cualquier cambio.</p>
	';

//	Help text for the edit field page.
$helptxt['customform_field_title'] = '
	<p>Este es el identificador que utilizará el mod para acceder a la entrada del usuario desde el formulario. No se muestra en el formulario ni en la publicación final del foro.</p>
	<p>Tenga en cuenta que el identificador solo lo ven los administradores y no los usuarios, se usa para designar dónde se muestra la entrada de los usuarios en el formulario y cuándo se publica un formulario enviado en el foro. Para obtener los mejores resultados, mantenga los títulos cortos, en minúsculas, y no use caracteres especiales como # & * @ [ etc.</p>
	<p>Ejemplo: nombre, nombre de usuario y nombre_de_usuario funcionan bien, pero "Nombre de usuario" no.</p>
	<p>Un identificador incorrecto hará que su formulario no funcione correctamente. Por ejemplo, la información que el usuario escribe en el formulario no se mostrará en la publicación del foro o es posible que el formulario no se muestre a los usuarios.</p>
	';
$helptxt['customform_type'] = '
	<p>Esta configuración le permite establecer el tipo de campo que se muestra. Restringiendo así la entrada que un usuario puede enviar.</p>
	<ul class="normallist">
		<li>
			<b>Texto</b> agrega un pequeño cuadro de entrada que permite al usuario escribir cualquier cosa.
		</li>
		<li>
			<b>Texto adicional</b> agrega un cuadro de entrada grande que permite al usuario escribir cualquier cosa con varias líneas permitidas.
		</li>
		<li>
			<b>Cuadro de verificación</b> publicará si la casilla se marcó o no una vez que se envió el formulario al foro. De forma predeterminada, publicará <b>sí</b> si está marcada y <b>no</b> si no está marcada.
		</li>
		<li>
			<b>Seleccionar cuadro</b> permitirá al usuario elegir entre varios elementos. Ingrese la lista de elementos que desea separar por comas en el campo <b>Parámetros de tipo adicionales</b>. La primera opción estará preseleccionada a menos que el usuario seleccione otra cosa.
		</li>
		<li>
			<b>Radio de botones</b> como un cuadro de selección permitirá al usuario elegir entre varios elementos. Ingrese la lista de elementos que desea separar por comas en el campo <b>Parámetros de tipo adicionales</b>. Ninguno de los elementos será preseleccionado.
		</li>
		<li>
			<b>Información</b> le permite mostrar texto en todo el formulario sin necesidad de ninguna intervención del usuario.
		</li>
	</ul>
	';
$helptxt['customform_type_vars'] = '
	<p>Este campo le permite establecer cualquier parámetro adicional necesario para cambiar el comportamiento del campo en el formulario y la publicación del foro según el tipo de campo.</p>
	<h3 class="largetext">Parámetros para cualquier campo</h3>
	<ul class="normallist">
		<li>
			<p><b>default=(str)</b> Esto le permite configurar el texto predeterminado que se mostrará en la publicación del foro si el usuario no completa la entrada.</p>
			<p>Ejemplo: si ingresa "default=User no ingresó ningún dato en este campo", en el campo Parámetros de tipo adicional y el usuario no ingresa ningún dato en el campo al completar el formulario, entonces "El usuario no ingresó ningún dato en este campo." se mostrará automáticamente en la publicación del foro.</p>
		</li>
		<li>
			<p><b>requerido</b> También puede ingresar "obligatorio" en el campo Parámetros de tipo adicional, lo que obligará al usuario a ingresar datos válidos para este campo antes de que se envíe el formulario.</p>
			<p>Los campos se indican en el formulario con un * y una nota junto al botón de enviar que indica * Campos obligatorios. Si el usuario no ingresa datos en esos campos, el formulario regresará con el <b><span style="color:red">*</span></b> se muestra en <b><span style="color:red">rojo</span></b>, recordando al usuario que esos campos deben completarse para poder enviar el formulario.</p>
		</li>
	</ul>
	<h3 class="largetext">Parámetros del cuadro de texto</h3>
	<ul class="normallist">
		<li>
			<p><b>size=(int)</b> Esto restringirá la cantidad de caracteres que un usuario puede escribir en su entrada.</p>
			<p>Ejemplo: si ingresara size=8 en el campo, la entrada de usuarios se limitaría a 8 caracteres. Entonces, si el usuario escribe 1234567890 en el campo, solo se mostrará 12345678 en la publicación del foro.</p>
		</li>
	</ul>
	<h3 class="largetext">Seleccionar cuadro o radio de botones</h3>
	<ul class="normallist">
		<li>
			<p>Seleccionar cuadro o radio de botones, te permitirá poner una serie de opciones (separadas por comas \' ), para que el usuario seleccione.</p>
			<p>Ejemplo: Artículo 1, Artículo 2, Artículo 3, Artículo 4, etc.</p>
			<p>Para requerir que un usuario use un cuadro de selección o radio de botones, ingrese "requerido" como la primera selección. Ingresar "obligatorio" en otra parte de la serie de opciones puede hacer que su formulario no funcione correctamente.</p>
			<p>Ejemplo: requerido, Artículo 1, Artículo 2, Artículo 3, Artículo 4</p>
		</li>
	</ul>
	<h3 class="largetext">Cuadro de verificación</h3>
	<ul class="normallist">
		<li>
			<p>De forma predeterminada, si deja vacío el campo Parámetros de tipo adicional, se mostrará un cuadro de verificación <b>Sí</b> si el cuadro está marcada en el formulario o <b>No</b> si no lo estaba.</p>
			<p>Alternativamente, un cuadro de verificación le permitirá poner dos cadenas, separadas por una coma, la primera cadena se mostrará si el cuadro de verificación está marcada, mientras que la segunda se mostrará si el cuadro de verificación no lo está..</p>
			<p>Ejemplo: El cuadro de verificación estaba marcada. El cuadro de verificación no estaba marcada.</p>
			<p>También puede usar el parámetro "obligatorio" para obligar al usuario a marcar el cuadro antes de enviar un formulario. De forma predeterminada, si solo ingresa "requerido" en el campo Parámetros de tipo adicional, el cuadro de verificación simplemente mostrará <b>requerido</b>, en la publicación del foro. También puede hacer que muestre algo de su elección en la publicación del foro.</p>
			<p>Ejemplo: se me pidió que marcara este cuadro.,obligatorio</p>
		</li>
	</ul>';
