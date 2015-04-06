function setLocationTitle(txt)
{
	var v=gObject("cgiLocation");
	if(v)v.innerHTML=txt;
}
function gObject(objnme)
{
    pos=objnme.indexOf("->");
    if(pos!=-1){ /* the layer in other frame */
        doc=objnme.substr(0,pos);
        d=eval(doc);
        objnm=objnme.substr(pos+2);
    }
    else {d=document;doc="document";objnm=objnme;}

    return d.getElementById(objnm);

}