<?xml version="1.0" encoding="UTF-8"?>

<!--<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<p>test</p>
		<xsl:for-each select="*/a">
			<p><xsl:value-of select="."/></p>
		</xsl:for-each>
	</xsl:template>
	<xsl:template match="text()"/>
</xsl:stylesheet>-->

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/*">
		<div id="tableDesMatieres">
			<div class="voirPlusMoins plie">
				<!--<xsl:for-each select="head">
					<div style="text-decoration: underline; margin-left:{@level*0.5}em;"><xsl:value-of select="." /></div>
				</xsl:for-each>-->
				<ul class="tableDesMatieres">
					<xsl:for-each select="tocitem">
						<li>
							<div>
								<span style="margin-left:{((item/@level)*0.6)}em;"><xsl:value-of select="item" /></span>
								<span><xsl:value-of select="page" /></span>
								<div class="clear" style="clear:both"></div>
							</div>
						</li>
					</xsl:for-each>
				</ul>
				<!--<xsl:value-of select="."/>-->
			</div>
			<button class="btn btn-small-link" onclick="voirPlusMoins(this);">Voir plus</button>
		</div>
		<br />
	</xsl:template>
</xsl:stylesheet>
