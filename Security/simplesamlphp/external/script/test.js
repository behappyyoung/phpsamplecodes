document.writeln('external');
document.writeln(document.referrer);
var referer = document.referrer;
if(/testing./i.test(referer)){
    alert('testing');   
}

