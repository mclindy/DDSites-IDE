<!--<canvas id="myth"></canvas>-->
<?php //require_once("templates/logo.php"); ?>
<script src="components/user/init.js"></script>

<form id="login" method="post" autocomplete="off">
	<fieldset style="border-color: #164f0c; border-width: 2px; background-color: rgba(255, 255, 255, .9); box-shadow: 0px 0px 15px black;">
		<legend style="-webkit-text-stroke: 1px #164f0c; text-stroke: 2px red; text-shadow: 0px 0px 5px black;">DDSites IDE</legend>
		<label for="username" style="color: #164f0c;"><i class="fas fa-user"></i><?php echo i18n("username"); ?></label>
		<input id="username" type="text" name="username" autofocus="autofocus" autocomplete="current-username" style="background-color: rgba(255, 255, 255, .7); color: #164f0c;">

		<label for="password" style="color: #164f0c;"><i class="fas fa-key"></i><?php echo i18n("password"); ?></label>
		<input id="password" type="password" name="password" autocomplete="current-password" style="background-color: rgba(255, 255, 255, .7); color: #164f0c;">
		<i for="password" class="fas fa-eye-slash merged-icon togglePassword" style="color: #164f0c;"></i>

		<div id="login_options">
			<label for="language" style="color: #164f0c;"><i class="fas fa-language"></i> <?php echo i18n("language"); ?></label>
			<select name="language" id="language">
				<?php
				$languages = $i18n->codes();
				foreach ($languages as $code => $lang) {

					$lang = ucfirst(strtolower($lang));

					$option = "<option value=\"$code\"";
					if ($code === "en") $option .= "selected";
					$option .= ">$lang</option>";

					echo $option;
				} ?>
			</select>
		</div>

		<input id="remember" type="checkbox" name="remember" class="large">
		<label for="remember" style="color: #164f0c;"><?php echo i18n("rememberMe"); ?></label>

		<button><?php echo i18n("login"); ?></button>
		<button id="show_login_options"><?php echo i18n("more"); ?></button>
		<button id="hide_login_options" style="display:none;"><?php echo i18n("less"); ?></button>
		<!--<a id="github_link" href="https://github.com/mclindy/DDSites-IDE" target="_blank" rel="noreferrer noopener" style="color: #164f0c;"><?php echo("v" . Common::version()); ?></a>-->

	</fieldset>
</form>
