<?xml version="1.0" encoding="ISO-8859-1" ?>
<!-- RSS generated by PHPBoost on {DATE_RFC822} -->

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{TITLE}</title>
        <atom:link href="{U_LINK}&amp;feed=rss" rel="self" type="application/rss+xml"/>
        <link>{HOST}</link>
        <description>{DESC}</description>
        <copyright>(C) 2005-2008 PHPBoost</copyright>
        <language>{LANG}</language>
        <generator>PHPBoost</generator>
        
        # START item #
        <item>
            <title>{item.TITLE}</title>
            <link>{item.U_LINK}</link>
            <guid>{item.U_GUID}</guid>
            <description>{item.DESC}</description>
            <pubDate>{item.DATE_RFC822}</pubDate>
        </item>
        # END item #
    </channel>
</rss>