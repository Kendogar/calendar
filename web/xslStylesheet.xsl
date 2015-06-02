<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet
        version="1.0"
        xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
        xmlns="http://www.w3.org/TR/REC-html40">
    <xsl:output method="html"/>

    <xsl:template match="/">
        <HTML>
            <HEAD>
                <link rel="stylesheet" type="text/css" href="/main.css"/>
                <TITLE>Calendarshit</TITLE>
            </HEAD>
            <BODY>
                <xsl:apply-templates/>
            </BODY>
        </HTML>
    </xsl:template>

    <xsl:template match="terminkalender">
        <div class="wrapper">
            <div class="innerWrapper">
                <xsl:apply-templates select="termin"/>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="termin">
        <div class="termin">
            <table>
                <xsl:apply-templates select="datum"/>
                <xsl:apply-templates select="zeit"/>
                <xsl:apply-templates select="titel"/>
                <xsl:apply-templates select="beschreibung"/>
            </table>
        </div>
    </xsl:template>

    <xsl:template match="datum">
        <tr>
            <td>
                <span class = "time">
                    <xsl:value-of select="start"/> - <xsl:value-of select="ende"/>
                </span>
            </td>
        </tr>
    </xsl:template>
    <xsl:template match="zeit">
        <tr>
            <td>
                <span class="time">
                    <xsl:value-of select="startzeit"/> - <xsl:value-of select="endzeit"/>
                </span>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="titel">
        <tr>
            <td>
                <h3>
                    <xsl:value-of select="."/>
                </h3>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="beschreibung">
        <tr>
            <td>
                <p>
                    <xsl:value-of select="."/>
                </p>
            </td>
        </tr>
    </xsl:template>


</xsl:stylesheet>