<?php

declare(strict_types=1);

/**
 * @package   Custom Form mod
 * @version   4.1.1
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

global $helptxt;

//	Header and General text for the Custom Form Mod settings area.
$txt['customform_generalsettings_heading'] = 'Custom-Form-Mod-Einstellungen';
$txt['customform_tabheader'] = 'Benutzerdefinierte Formulare';
$txt['customform_update_available'] = 'Aktualisierung verfügbar!';
$txt['customform_update_action'] = 'Auf %s aktualisieren';
$txt['customform_update_later'] = 'Später erinnern';
$txt['customform_form'] = 'Formular';
$txt['customform_field'] = 'Feld';
$txt['customform_current_version'] = 'Current version: {version}';
$txt['customform_view_title'] = 'Formulartitel anzeigen';
$txt['customform_view_title_desc'] = 'Wählen Sie den Titel für die Aktion/Seite, die einem Benutzer die Formularliste anzeigt (unter "index.php?action=form"). Standard ist <b>Benutzerdefinierte Formulare</b>.';
$txt['customform_view_text'] = 'Formulartext anzeigen';
$txt['customform_view_text_desc'] = 'Wählen Sie den Erklärungstext für die Aktion/Seite, die einem Benutzer die Formularliste anzeigt (unter "index.php?action=form").';
$txt['customform_view_perms'] = 'Formularansicht-Seitenberechtigungen';
$txt['customform_view_perms_desc'] = 'Beschränken Sie die Mitgliedergruppen, die die Formularliste sehen können. Individuelle Formulare erben dies nicht.';

//	Stuff for the forms action
$txt['customform_submit'] = 'Formular absenden';
$txt['customform_required'] = 'Erforderliche Felder sind mit einem Stern (*) markiert';

//	Junk for the list areas.
$txt['customform_listheading_fields'] = 'Formularfelder';
$txt['customform_add_form'] = 'Neues Formular hinzufügen';
$txt['customform_add_field'] = 'Neues Feld hinzufügen';
$txt['customform_edit'] = 'Bearbeiten';
$txt['customform_delete_warning'] = 'Möchten Sie dies wirklich löschen?';
$txt['customform_list_noelements'] = 'Diese Liste ist derzeit leer.';
$txt['customform_moveup'] = 'Nach oben verschieben';
$txt['customform_movedown'] = 'Nach unten verschieben';

//	Text for the settings pages.
$txt['customform_subject'] = 'Formular-Betreff';
$txt['customform_subject_desc'] = 'Betreff der letzten Nachricht. Unterstützt Makros für Feldnamen.';
$txt['customform_output_type'] = 'Formular-Ausgabemethode';
$txt['customform_output_type_desc'] = 'Legen Sie fest, wie Formularausgabe gesendet wird.';
$txt['custom_form_output_forum_post'] = 'Forumsbeitrag';
$txt['customform_output'] = 'Formular-Ausgabe';
$txt['customform_output_desc'] = 'Geben Sie den Identifikator des Feldes innerhalb eines Klammerpaares <code>{{ name }}</code> ein, um die im Formular eingegebenen Daten anzuzeigen.';
$txt['customform_board'] = 'Board, in das geschrieben werden soll';
$txt['customform_board_desc'] = 'Wählen Sie das Board, in das das Formular posten soll. Das Zielboard kann versteckt sein.';
$txt['customform_template_function'] = 'Benutzerdefinierte Vorlagenfunktion';
$txt['customform_exit'] = 'Weiterleitung absenden';

//  Redirection Text
$txt['customform_redirect_forum'] = 'Forum';
$txt['customform_redirect_topic'] = 'Thema';
$txt['customform_redirect_board'] = 'Board (Standard)';
$txt['customform_redirect_thanks'] = 'Vielen Dank';
$txt['customform_redirect_list'] = 'Formularliste';

//  Text for the thankyou page
$txt['customform_thankyou'] = '
	<p>Die von Ihnen eingegebenen Informationen wurden übermittelt.</p>
	<p>Vielen Dank, dass Sie sich die Zeit genommen haben, dieses Formular auszufüllen.</p>
	<p>Sie können jetzt zum Forum zurückkehren oder die Formularliste ansehen, falls verfügbar.</p>';

//	Options for the field edit page.
$txt['customform_character_warning'] = 'Der Identifikator dieses Feldes enthält ungültige Zeichen.';
$txt['customform_current_identifier'] = 'Ihr Identifikator ist %s';
$txt['customform_suggested_identifier'] = 'Vorgeschlagener Identifikator ist %s';

$txt['customform_identifier'] = 'Identifikator';
$txt['customform_text'] = 'Beschreibung';
$txt['customform_text_desc'] = 'Eine Beschreibung des Feldes, die dem Benutzer angezeigt wird, wenn er die Informationen eingibt.';
$txt['customform_type'] = 'Typ';
$txt['customform_type_vars'] = 'Zusätzliche Typparameter';
$txt['custom_form_fields_text'] = 'Text';
$txt['custom_form_fields_textarea'] = 'Langer Text';
$txt['custom_form_fields_select'] = 'Auswahlliste';
$txt['custom_form_fields_radio'] = 'Optionsfelder (Radio Buttons)';
$txt['custom_form_fields_check'] = 'Auswahlfeld (Checkbox)';
$txt['custom_form_fields_info'] = 'Informationen';
$txt['customform_max_length'] = 'Maximale Länge';
$txt['customform_max_length_desc'] = '(0 für kein Limit)';
$txt['customform_dimension'] = 'Dimensionen';
$txt['customform_dimension_row'] = 'Zeilen';
$txt['customform_dimension_col'] = 'Spalten';
$txt['customform_size'] = 'Maximale Anzahl von Zeichen';
$txt['customform_bbc'] = 'BBC erlauben';
$txt['customform_options'] = 'Optionen';
$txt['customform_options_desc'] = 'Optionen-Feld leer lassen, um es zu entfernen. Auswahl wählt die Standardeinstellung.';
$txt['customform_options_more'] = 'Mehr';
$txt['customform_default'] = 'Standardzustand';
$txt['customform_active'] = 'Aktiv';
$txt['customform_active_desc'] = 'Dieses Feld wird deaktiviert, wenn nicht ausgewählt.';
$txt['customform_mask'] = 'Eingabemaske';
$txt['customform_mask_desc'] = 'Dies überprüft die Benutzereingaben.';
$txt['customform_mask_number'] = 'Ganze Zahl (wissenschaftliche Schreibweise nicht erlaubt)';
$txt['customform_mask_float'] = 'Gleitkommazahl (Dezimalstellen erlaubt)';
$txt['customform_mask_email'] = 'E-Mail (Muss kürzer als 255 Zeichen sein)';
$txt['customform_mask_regex'] = 'Regulärer Ausdruck (nur für Experten!)';
$txt['customform_regex'] = 'Regulärer Ausdruck';
$txt['customform_regex_desc'] = 'Überprüfen Sie auf Ihre eigene Weise.';

// Validation errors
$txt['customform_error_title'] = 'Ups, Fehler sind aufgetreten!';
$txt['customform_invalid_value'] = 'Der Wert, den Sie für %1$s gewählt haben, ist ungültig.';

// argument(s): $scripturl
$txt['whoallow_form'] = 'Schaut die <a href="%s?action=form">Formularliste</a> an.';
// argument(s): $scripturl, $id_form, $title
$txt['customform_who'] = 'Schaut <a href="%s?action=form;n=%s">%s</a> an.';

//	Help text for the edit form page.
$helptxt['customform_output'] = '
	<p>Dies ist das Format, in dem die von Benutzern im Formular eingegebenen Daten im Forumsbeitrag angezeigt werden, nachdem das Formular abgeschickt wurde.</p>
	<p>Um tatsächlich Daten anzuzeigen, die ein Benutzer in den Forumsbeitrag eingibt, müssen Sie den Titel des Feldes innerhalb von Klammern { } eingeben.</p>
	<p><b>Beispiel</b>: Falls das Feld "name" heisst, dann wird <code>{{ name }}</code> durch das ersetzt, was der Benutzer im Formularfeld eingegeben hat.</p>
	<p><code class="tfacode">Mein Name ist {{ name }}.</code></p>
	<p>Wenn der Benutzer im entsprechenden Formularfeld "Bob" eingibt, würde der Forumsbeitrag "Mein Name ist Bob" anzeigen.</p>
	';
$helptxt['customform_submit_exit'] = '
	<p>Mit dieser Einstellung können Sie auswählen, wohin der Benutzer nach erfolgreichem Ausfüllen des Formulars gesendet wird.</p>
	<p>Im Folgenden sind mehrere Makros aufgelistet, die Sie verwenden können.</p>
	<ul class="normallist">
		<li>
			<b>board</b>
			&nbsp;- wird den Benutzer in das Board umleiten, in dem das Formular veröffentlicht wird (dies ist auch die Standardeinstellung, wenn das Feld leer gelassen wird).
		</li>
		<li><b>forum</b>
			&nbsp;- wird den Benutzer zur Hauptseite des Forums weiterleiten.
		</li>
		<li><b>form</b>
			&nbsp;- wird den Benutzer auf die Liste der verfügbaren Formulare umleiten, die er ausfüllen kann, falls er die Erlaubnis hat, sie anzusehen.
		</li>
		<li><b>thanks</b>
			&nbsp;- wird den Benutzer auf eine einfache Seite umleiten, die ihm mitteilt, dass das Formular korrekt ausgefüllt wurde und ihm für das Ausfüllen des Formulars dankt.
		</li>
	</ul>
	<p>forum, formular und thanks sind nützlich, wenn das Formular in ein Board postet, auf das der Benutzer keinen Zugriff hat.</p>
	<p>Sie können auch eine URL wie <code>https://www.example.com/</code> eingeben, an die der Benutzer weitergeleitet wird. Dies kann nützlich sein, um Benutzer zu einer benutzerdefinierten Dankesseite, einem anderen spezifischen Formular, einem bestimmtem Forumsbeitrag oder irgendeinem anderen Ort im Internet weiterzuleiten.</p>
	';
$helptxt['customform_template_function'] = '
	<p>Benutzerdefinierte Vorlagen-Funktionen können zu <code>./themes/default/CustomFormUserland.template.php</code> hinzugefügt werden. Bitte beachten Sie, dass die Template-Funktion, die verwendet werden wird, nach dem Format <code>template_{Wert für diese Einstellung}</code> benannt sein muss, andernfalls wird die Standard-Template-Funktion <code>template_form_my_custom_template()</code> verwendet werden.</p>
	<p>Sie können dann die Dokumentation dieser Funktion nutzen, um zu sehen wie Informationen von der Mod an sie übergeben werden, um sie für Ihre Zwecke anzupassen.</p>
	<p>Für ein kurzes Beispiel, was innerhalb einer Vorlage angepasst werden kann, geben Sie "example" in die benutzerdefinierte Vorlagenfunktion eines Formulars ein und schauen sich das Formular anschließend an. Sie werden diverse Stellen mit dem Text <span style="color:red">"Example of something"</span>sehen. Diese sind ein guter Ort um Informationen zu Ihrer Formularvorlage hinzuzufügen, ohne die Funktionen des Formulars selbst zu beeinträchtigen.</p>
	<p class="infobox"><b><span style="color:red">Wichtig</span></b>: Sie sollten grundlegende Kenntnisse von HTML und PHP besitzen, bevor Sie zu drastische Änderungen vornehmen.</p>
	<p class="noticebox">Fehlerhafter Code führt dazu, dass die Custom-Form-Mod und möglicherweise Ihr Forum nicht mehr funktionieren! Daher sollten Sie ein Backup Ihres Formulars oder zumindest von <code>./themes/default/CustomFormUserland.template.php</code> erstellen, bevor Sie Änderungen vornehmen.</p>
	';

//	Help text for the edit field page.
$helptxt['customform_field_title'] = '
	<p>Dies ist der Identifikator, der von der Mod verwendet wird, um auf die Benutzereingabe des Formulars zuzugreifen. Er wird nicht im Formular oder im finalen Forumsbeitrag angezeigt.</p>
	<p>Beachten Sie, dass der Identifikator nur für Administratoren und nicht für Benutzern sichtbar ist. Er wird verwendet, um zu bestimmen, wo die Benutzereingaben im Formular angezeigt werden und wann ein abgesendetes Formular im Forum veröffentlicht wird. Für die besten Ergebnisse halten Sie die Titel kurz, in Kleinbuchstaben und verwenden keine Sonderzeichen wie # & * @ [ etc.</p>
	<p>Beispiel: name, username und user_name funktionieren gut, aber "User Name" nicht.</p>
	<p>Ein fehlerhafter Identifikator führt dazu, dass Ihr Formular nicht richtig funktioniert. Zum Beispiel werden die Informationen, die die Benutzer im Formular eingeben, nicht im Forumsbeitrag angezeigt werden oder das Formular kann den Benutzern überhaupt nicht angezeigt werden.</p>
	';
$helptxt['customform_type'] = '
	<p>Mit dieser Einstellung können Sie den Feldtyp festlegen, der angezeigt wird. Dadurch werden die Benutzereingaben eingeschränkt.</p>
	<ul class="normallist">
		<li>
			<b>Text</b> fügt ein kleines Eingabefeld hinzu, mit dem der Benutzer etwas eingeben kann.
		</li>
		<li>
			<b>Großer Text</b> fügt ein großes Eingabefeld hinzu, das es dem Benutzer erlaubt, etwas einzugeben, das mehreren Zeilen enthalten kann.
		</li>
		<li>
			<b>Auswahlfeld (Checkbox)</b> wird veröffentlichen, ob das Kästchen markiert wurde oder nicht, sobald das Formular ins Forum abgeschickt wurde. Standardmäßig wird <b>yes</b> gepostet falls es ausgewählt wurde und <b>no</b> wenn nicht.
		</li>
		<li>
			<b>Auswahlliste (Select Box)</b> ermöglicht dem Benutzer, aus verschiedenen Elementen auszuwählen. Geben Sie die Liste der gewünschten Elemente durch Kommas getrennt in das Feld <b>Zusätzliche Typparameter</b> ein. Die erste Option wird vorausgewählt, es sei denn, der Benutzer wählt etwas anderes.
		</li>
		<li>
			<b>Optionsfelder (Radio Buttons)</b> erlauben es dem Benutzer, wie auch die Auswahlliste, aus verschiedenen Elementen auszuwählen. Geben Sie die Liste der gewünschten Elemente durch Kommas getrennt in das Feld <b>Zusätzliche Typparameter</b> ein. Keins der Elemente wird vorausgewählt.
		</li>
		<li>
			<b>Informationen</b> ermöglicht Ihnen die Anzeige von Text innerhalb des Formulars ohne Benutzereingaben zu erfordern.
		</li>
	</ul>
	<b>';
$helptxt['customform_type_vars'] = '
	<p>Dieses Feld erlaubt es Ihnen, beliebige zusätzlich benötigte Parameter zu setzen, um das Verhalten des Formularfelds und des Forumsbeitrags basierend auf dem Feldtyp zu verändern.</p>
	<h3 class="largetext">Parameter für beliebige Felder</h3>
	<ul class="normallist">
		<li>
			<p><b>default=(str)</b> Dies erlaubt es Ihnen, einen Standardtext anzugeben, der im Forumsbeitrag angezeigt wird, falls der Benutzer versäumt etwas einzutragen.</p>
			<p>Beispiel: Falls Sie im Zusätzliche-Typparameter-Feld "default=Benutzer hat keine Daten in dieses Feld eingegeben." eingeben und der Benutzer beim Ausfüllen des Formulars nichts in das Feld eingibt, dann wird im Forumsbeitrag automatisch "Benutzer hat keine Daten in dieses Feld eingegeben." angezeigt werden.</p>
		</li>
		<li>
			<p><b>required</b> Sie können außerdem "required" in das Zusätzliche-Typparameter-Feld eingeben um den Benutzer zu zwingen, gültige Daten für dieses Feld einzugeben, bevor sich das Formular abschicken lässt.</p>
			<p>Diese Felder sind im Formular mit einem * und einem Hinweis nahe des Abschicken-Buttons, wenn erforderliche Felder nicht ausgefüllt wurden, gekennzeichnet. Falls der Benutzer eins dieser Felder nicht ausfüllt, werden die <b><span style="color:red">*</span></b>e in <b><span style="color:red">rot</span></b> angezeigt, um den Benutzer daran zu erinnern, dass das Ausfüllen notwendig ist, um das Formular abschicken zu können.</p>
		</li>
	</ul>
	<h3 class="largetext">Textfeld-Parameter</h3>
	<ul class="normallist">
		<li>
			<p><b>size=(int)</b> Dies beschränkt die Anzahl der Zeichen, die ein Benutzer in seinem Beitrag schreiben kann.</p>
			<p>Beispiel: Wenn Sie size=8 in dieses Feld eintragen, dann ist die Benutzereingabe auf 8 Zeichen begrenzt. Wenn ein Benutzer dann 1234567890 in das Feld eingibt, wird im Forumsbeitrag nur 12345678 angezeigt werden.</p>
		</li>
	</ul>
	<h3 class="largetext">Auswahlliste oder Optionsfelder</h3>
	<ul class="normallist">
		<li>
			<p>Eine Auswahlliste oder Optionsfelder (Radio Buttons) erlauben es Ihnen, eine Reihe von Optionen (getrennt durch Kommas) für den Benutzer anzubieten.</p>
			<p>Beispiel: Punkt 1, Punkt 2, Punkt 3, Punkt 4 und so weiter.</p>
			<p>Um die Benutzung einer Auswahlliste oder eines Optionsfelds zu erzwingen, geben Sie als erste Option "required" an. Wenn Sie "required" an anderer Stelle angeben, funktioniert Ihr Formular möglicherweise nicht korrekt.</p>
			<p>Beispiel: required, Punkt 1, Punkt 2, Punkt 3, Punkt 4 </p>
		</li>
	</ul>
	<h3 class="largetext">Auswahlfeld (Checkbox)</h3>
	<ul class="normallist">
		<li>
			<p>Standardmäßig, wenn Sie das Zusätzliche-Typparameter-Feld leer lassen, wird ein Auswahlfeld <b>Yes</b> posten, wenn das Auswahlfeld im Formular angekreuzt ist oder <b>No</b> falls nicht.</p>
			<p>Alternativ erlaubt ein Auswahlfeld es Ihnen, zwei Zeichenketten, getrennt durch ein Komma, anzugeben. Die erste Zeichenkette wird angezeigt, wenn das Auswahlfeld angekreuzt ist, wohingegen die zweite gezeigt wird, wenn nicht.</p>
			<p>Beispiel: Das Auswahlfeld wurde ausgewählt.,Das Auswahlfeld wurde nicht ausgewählt.</p>
			<p>Sie können außerdem den "required"-Parameter verwenden, um den Benutzer zu zwingen, das Auswahlfeld anzukreuzen, bevor ein Formular abgeschickt wird. Standardmäßig, wenn Sie nur "required" in das Zusätzliche-Parameter-Feld eingeben, wird das Auswahlfeld im Forumsbeitrag einfach <b>required</b> anzeigen. Sie können es auch einen Text Ihrer Wahl im Forumsbeitrag anzeigen lassen.</p>
			<p>Beispiel: Ich musste dieses Auswahlfeld ankreuzen.,required</p>
		</li>
	</ul>';
