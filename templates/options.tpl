<h3>{Configuración}</h3>
	<form name="set_publicame_options" method="POST" action="">
	<input type="hidden" name="do" value="save_publicame_config" style="display:none;" />
	<fieldset>
		<table>
			<tr>
				<td class="vertalign"><label for="publicame_username">{Nombre de usuario:}</label></td>
				<td>
					<input type="text" name="publicame_username" id="publicame_username" value="{@publicame_username}" />
					<br /><i>{Tu nombre de usuario en pub.lica.me}</i>
				</td>
			</tr>
			<tr>
				<td class="vertalign"><label for="publicame_pass">{Contraseña:}</label></td>
				<td>
					<input type="password" name="publicame_pass" id="publicame_pass" value="{@publicame_pass}" />
					<br /><i>{Tu contraseña en pub.lica.me}</i>
				</td>
			</tr>
			<tr>
				<td class="vertalign"><label for="publicame_errors">{No. de errores:}</label></td>
				<td>
					<input type="text" name="publicame_errors" id="publicame_errors" value="{@publicame_errors}" />
					<br /><i>{En caso de que se produzcan errores al publicar, se mostrará esta cantidad de errores en la página de estadísticas.<br />El valor predeterminado es 10.}</i>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value=" {Guardar} " id="publicame_options_submit" /></td>
			</tr>
		</table>
	</fieldset>
</form>