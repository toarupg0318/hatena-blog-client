<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaSuperPreDOMElement;

it('test HatenaSuperPreDOMElement', function () {
    $value = <<<HATENA
  #!/usr/bin/perl -w
  use strict;
  print <<END;
  <html><body>
    <h1>Hello! World.</h1>
  </body></html>
  END
HATENA;
    $hatenaSuperPreDOMElement = new HatenaSuperPreDOMElement($value);
    expect(strval($hatenaSuperPreDOMElement))
        ->toBe(
            <<<HATENA
>||
  #!/usr/bin/perl -w
  use strict;
  print <<END;
  <html><body>
    <h1>Hello! World.</h1>
  </body></html>
  END
||<
HATENA
        );
});