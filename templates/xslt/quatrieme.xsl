<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<div id="quatrieme">
			<div class="voirPlusMoins plie">
				<xsl:copy-of select="." />
			</div>
			<button class="btn btn-small-link" onclick="voirPlusMoins(this);">Voir plus</button>
		</div>
		<br />
	</xsl:template>
</xsl:stylesheet>
