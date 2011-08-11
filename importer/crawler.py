import urllib2

class Crawler:

    def __init__(self, startUrl):
        self.seedUrl = startUrl
        
    def download(self, url):
        try:
            req = urllib2.Request(url)
            req.add_header('User-Agent','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.107 Safari/535.1')
            fp = urllib2.urlopen(req)
            text = ''
            while 1:
                s = fp.read()
                if not s:
                    break
                text = text + '\n' + s
            fp.close()
            return text
        except urllib2.HTTPError, e:
            print e.code
            return ''
        
