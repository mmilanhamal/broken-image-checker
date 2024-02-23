onunload = function()
{
    var foo = document.getElementById('foo');
    self.name = 'fooidx' + foo.selectedIndex;
}

onload = function()
{
    var idx, foo = document.getElementById('foo');
    foo.selectedIndex = (idx = self.name.split('fooidx')) ? idx : 0;
}
