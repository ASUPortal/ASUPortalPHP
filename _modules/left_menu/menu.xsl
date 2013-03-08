<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
 <xsl:output method="html" encoding="UTF-8" indent="no"/>
 <xsl:template match="menu/item">
  <div>  
    <span><xsl:value-of select="@name"/></span>
    <xsl:for-each select="item">
      <a href="{@href}" title="{@title}"><xsl:value-of select="@name"/></a>    
    </xsl:for-each>
  </div>   
 </xsl:template>
</xsl:stylesheet>