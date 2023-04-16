<?php

declare(strict_types=1);

namespace Toarupg0318\HatenaBlogClient\ValueObjects\DOM;

use Stringable;

final class HatenaSyntaxHighLightDOMElement extends HatenaDOMElement
{
    /** @var string[] */
    public const LANGUAGES_TO_HANDLE = [
        'a2ps', 'a65', 'aap', 'abap', 'abaqus', 'abc', 'abel', 'acedb', 'actionscript',
        'ada', 'aflex', 'ahdl', 'alsaconf', 'amiga', 'aml', 'ampl', 'ant', 'antlr',
        'apache', 'apachestyle', 'applescript', 'arch', 'art', 'asm', 'asm68k', 'asmh8300',
        'asn', 'aspperl', 'aspvbs', 'asterisk', 'asteriskvm', 'atlas', 'autohotkey', 'autoit',
        'automake', 'ave', 'awk', 'ayacc','b', 'baan', 'basic', 'bc', 'bdf', 'bib', 'bindzone',
        'blank', 'brainfuck', 'bst', 'btm', 'bzr', 'c', 'cabal', 'calendar', 'catalog', 'cdl',
        'cdrdaoconf', 'cdrtoc', 'cf', 'cfg', 'ch', 'chaiscript', 'change', 'changelog', 'chaskell',
        'cheetah', 'chill', 'chordpro', 'cl', 'clean', 'clipper', 'clojure', 'cmake', 'cmusrc',
        'cobol', 'coco', 'coffee', 'colortest', 'conaryrecipe', 'conf', 'config', 'context',
        'cpp', 'crm', 'crontab', 'crystal', 'cs', 'csc', 'csh', 'csp', 'css', 'cterm', 'ctrlh',
        'cucumber', 'cuda', 'cupl', 'cuplsim', 'cvs', 'cvsrc', 'cweb', 'cynlib', 'cynpp', 'd',
        'dart', 'dcd', 'dcl', 'debchangelog', 'debcontrol', 'debsources', 'def', 'denyhosts',
        'desc', 'desktop', 'dictconf', 'dictdconf', 'diff', 'dircolors', 'diva', 'django',
        'dns', 'docbk', 'docbksgml', 'docbkxml', 'dosbatch', 'dosini', 'dot', 'doxygen',
        'dracula', 'dsl', 'dtd', 'dtml', 'dtrace', 'dylan', 'dylanintr', 'dylanlid', 'ecd',
        'edif', 'eiffel', 'elf', 'elinks', 'elixir', 'elmfilt', 'erlang', 'eruby', 'esmtprc',
        'esqlc', 'esterel', 'eterm', 'eviews', 'exim', 'expect', 'exports', 'fasm', 'fdcc',
        'fetchmail', 'fgl', 'flexwiki', 'focexec', 'form', 'forth', 'fortran', 'foxpro',
        'framescript', 'freebasic', 'fsharp', 'fstab', 'fvwm', 'fvwm2m4', 'gdb', 'gdmo', 'gedcom',
        'git', 'gitcommit', 'gitconfig', 'gitrebase', 'gitsendemail', 'gkrellmrc', 'gnuplot',
        'go', 'gp', 'gpg', 'grads', 'gretl', 'groff', 'groovy', 'group', 'grub', 'gsp', 'gtkrc',
        'haml', 'hamster', 'haskell', 'haste', 'hastepreproc', 'hatena', 'haxe', 'hb', 'hcl',
        'help', 'hercules', 'hex', 'hitest', 'hog', 'hostconf', 'hss', 'html', 'htmlcheetah',
        'htmldjango', 'htmlm4', 'htmlos', 'hxml', 'ia64', 'ibasic', 'icemenu', 'icon', 'idl',
        'idlang', 'indent', 'inform', 'initex', 'initng', 'inittab', 'io', 'ipfilter', 'ishd',
        'iss', 'ist', 'jal', 'jam', 'jargon', 'java', 'javacc', 'javascript', 'jess', 'jgraph',
        'jproperties', 'json', 'jsp', 'julia', 'kconfig', 'kix', 'kotlin', 'kscript', 'kwt', 'lace',
        'latte', 'ld', 'ldapconf', 'ldif', 'lex', 'lftp', 'lhaskell', 'libao', 'lifelines', 'lilo',
        'limits', 'lisp', 'lite', 'litestep', 'loginaccess', 'logindefs', 'logtalk', 'lotos',
        'lout', 'lpc', 'lprolog', 'lscript', 'lsl', 'lss', 'lua', 'lynx', 'm4', 'mail', 'mailaliases',
        'mailcap', 'make', 'man', 'manconf', 'manual', 'maple', 'markdown', 'masm', 'mason', 'master',
        'matlab', 'maxima', 'mel', 'messages', 'mf', 'mgl', 'mgp', 'mib', 'mma', 'mmix', 'mmp',
        'modconf', 'model', 'modsim3', 'modula2', 'modula3', 'monk', 'moo', 'mp', 'mplayerconf',
        'mrxvtrc', 'msidl', 'msmessages', 'msql', 'mupad', 'mush', 'muttrc', 'mysql', 'named', 'nanorc',
        'nasm', 'nastran', 'natural', 'ncf', 'netrc', 'netrw', 'nginx', 'nim', 'nosyntax', 'nqc',
        'nroff', 'nsis', 'obj', 'objc', 'objcpp', 'ocaml', 'occam', 'omnimark', 'openroad', 'opl',
        'ora', 'pamconf', 'papp', 'pascal', 'passwd', 'pcap', 'pccts', 'pdf', 'perl', 'perl6',
        'pf', 'pfmain', 'php', 'phtml', 'pic', 'pike', 'pilrc', 'pine', 'pinfo', 'plaintex',
        'plm', 'plp', 'plsql', 'po', 'pod', 'postscr', 'pov', 'povini', 'ppd', 'ppwiz', 'prescribe',
        'privoxy', 'processing', 'procmail', 'progress', 'prolog', 'promela', 'proto', 'protocols',
        'ps1', 'ps1xml', 'psf', 'ptcap', 'purifylog', 'pyrex', 'python', 'qf', 'quake', 'r', 'racc',
        'radiance', 'ratpoison', 'rc', 'rcs', 'rcslog', 'readline', 'rebol', 'registry', 'remind',
        'resolv', 'reva', 'rexx', 'rhelp', 'rib', 'rnc', 'rnoweb', 'robots', 'rpcgen', 'rpl', 'rst',
        'rtf', 'ruby', 'rust', 'samba', 'sas', 'sass', 'sather', 'scala', 'scheme', 'scilab', 'screen',
        'sd', 'sdc', 'sdl', 'sed', 'sendpr', 'sensors', 'services', 'setserial', 'sgml', 'sgmldecl',
        'sgmllnx', 'sh', 'sicad', 'sieve', 'simula', 'sinda', 'sindacmp', 'sindaout', 'sisu', 'skill',
        'sl', 'slang', 'slice', 'slpconf', 'slpreg', 'slpspi', 'slrnrc', 'slrnsc', 'sm', 'smarty', 'smcl',
        'smil', 'smith', 'sml', 'snnsnet', 'snnspat', 'snnsres', 'snobol4', 'spec', 'specman', 'spice',
        'splint', 'spup', 'spyce', 'sql', 'sqlanywhere', 'sqlforms', 'sqlinformix', 'sqlj', 'sqloracle',
        'sqr', 'squid', 'sshconfig', 'sshdconfig', 'st', 'stata', 'stp', 'strace', 'sudoers', 'svg', 'svn',
        'swift', 'syncolor', 'synload', 'syntax', 'sysctl', 'tads', 'tags', 'tak', 'takcmp', 'takout',
        'tar', 'taskdata', 'taskedit', 'tasm', 'tcl', 'tcsh', 'terminfo', 'terraform', 'tex', 'texinfo',
        'texmf', 'tf', 'tidy', 'tilde', 'tli', 'tpp', 'trasys', 'trustees', 'tsalt', 'tsscl', 'tssgm',
        'tssop', 'typescript', 'uc', 'udevconf', 'udevperm', 'udevrules', 'uil', 'updatedb', 'valgrind',
        'vb', 'vera', 'verilog', 'verilogams', 'vgrindefs', 'vhdl', 'vim', 'viminfo', 'virata', 'vmasm',
        'voscm', 'vrml', 'vsejcl', 'wdiff', 'web', 'webmacro', 'wget', 'whitespace', 'winbatch', 'wml',
        'wsh', 'wsml', 'wvdial', 'xbl', 'xdefaults', 'xf86conf', 'xhtml', 'xinetd', 'xkb', 'xmath',
        'xml', 'xmodmap', 'xpm', 'xpm2', 'xquery', 'xs', 'xsd', 'xslt', 'xxd', 'yacc', 'yaml',
        'z8a', 'zig', 'zir', 'zsh'
    ];

    /**
     * @param string|null $value
     * @param value-of<self::LANGUAGES_TO_HANDLE> $language
     */
    public function __construct(
        private readonly string|null $value,
        private readonly string $language
    ) {
        // todo: self::LANGUAGES_TO_HANDLE以外のやつ来たらthrow ex
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return <<<HATENA
>|{$this->language}|
$this->value
||<
HATENA;

    }
}