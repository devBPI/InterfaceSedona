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
	<xsl:template match="div">
		<div id="tableDesMatieres">
			<div class="voirPlusMoins plie">
				<ul class="tableDesMatieres">
					<xsl:for-each select="*">
						<li>
							<xsl:copy-of select="." />
								<!--<span style="margin-left: 0em; padding-right: 2em;"><xsl:value-of select="*[name( ) != 'span']" /></span>-->
								<!--<span style="margin-left: 0em;"><xsl:value-of select="." /></span>
								<xsl:if test="span">
									<span><xsl:value-of select="span" /></span>
								</xsl:if>
								<div class="clear" style="clear:both"></div>-->
						</li>
					</xsl:for-each>
				</ul>
			</div>
			<button class="btn btn-small-link" onclick="voirPlusMoins(this);">Voir plus</button>
		</div>
		<br />
	</xsl:template>
</xsl:stylesheet>
