<?php exit() ?>--by UglyOldGuy 86.127.152.145
_G.AQLoader=function(a,b)
function Dente(da,_b)
local function ab(db)
local _c=type(db)=="table"and{table.unpack(db)}or{string.byte(db,1,
#db)}
local ac,bc,cc={[0]=0,[1]=1,[2]=2,[3]=3,[4]=4,[5]=5,[6]=6,[7]=7,[8]=8,[9]=9,[10]=10,[11]=11,[12]=12,[13]=13,[14]=14,[15]=15,[16]=16,[17]=17,[18]=18,[19]=19,[20]=20,[21]=21,[22]=22,[23]=23,[24]=24,[25]=25,[26]=26,[27]=27,[28]=28,[29]=29,[30]=30,[31]=31,[32]=32,[33]=33,[34]=34,[35]=35,[36]=36,[37]=37,[38]=38,[39]=39,[40]=40,[41]=41,[42]=42,[43]=43,[44]=44,[45]=45,[46]=46,[47]=47,[48]=48,[49]=49,[50]=50,[51]=51,[52]=52,[53]=53,[54]=54,[55]=55,[56]=56,[57]=57,[58]=58,[59]=59,[60]=60,[61]=61,[62]=62,[63]=63,[64]=64,[65]=65,[66]=66,[67]=67,[68]=68,[69]=69,[70]=70,[71]=71,[72]=72,[73]=73,[74]=74,[75]=75,[76]=76,[77]=77,[78]=78,[79]=79,[80]=80,[81]=81,[82]=82,[83]=83,[84]=84,[85]=85,[86]=86,[87]=87,[88]=88,[89]=89,[90]=90,[91]=91,[92]=92,[93]=93,[94]=94,[95]=95,[96]=96,[97]=97,[98]=98,[99]=99,[100]=100,[101]=101,[102]=102,[103]=103,[104]=104,[105]=105,[106]=106,[107]=107,[108]=108,[109]=109,[110]=110,[111]=111,[112]=112,[113]=113,[114]=114,[115]=115,[116]=116,[117]=117,[118]=118,[119]=119,[120]=120,[121]=121,[122]=122,[123]=123,[124]=124,[125]=125,[126]=126,[127]=127,[128]=128,[129]=129,[130]=130,[131]=131,[132]=132,[133]=133,[134]=134,[135]=135,[136]=136,[137]=137,[138]=138,[139]=139,[140]=140,[141]=141,[142]=142,[143]=143,[144]=144,[145]=145,[146]=146,[147]=147,[148]=148,[149]=149,[150]=150,[151]=151,[152]=152,[153]=153,[154]=154,[155]=155,[156]=156,[157]=157,[158]=158,[159]=159,[160]=160,[161]=161,[162]=162,[163]=163,[164]=164,[165]=165,[166]=166,[167]=167,[168]=168,[169]=169,[170]=170,[171]=171,[172]=172,[173]=173,[174]=174,[175]=175,[176]=176,[177]=177,[178]=178,[179]=179,[180]=180,[181]=181,[182]=182,[183]=183,[184]=184,[185]=185,[186]=186,[187]=187,[188]=188,[189]=189,[190]=190,[191]=191,[192]=192,[193]=193,[194]=194,[195]=195,[196]=196,[197]=197,[198]=198,[199]=199,[200]=200,[201]=201,[202]=202,[203]=203,[204]=204,[205]=205,[206]=206,[207]=207,[208]=208,[209]=209,[210]=210,[211]=211,[212]=212,[213]=213,[214]=214,[215]=215,[216]=216,[217]=217,[218]=218,[219]=219,[220]=220,[221]=221,[222]=222,[223]=223,[224]=224,[225]=225,[226]=226,[227]=227,[228]=228,[229]=229,[230]=230,[231]=231,[232]=232,[233]=233,[234]=234,[235]=235,[236]=236,[237]=237,[238]=238,[239]=239,[240]=240,[241]=241,[242]=242,[243]=243,[244]=244,[245]=245,[246]=246,[247]=247,[248]=248,[249]=249,[250]=250,[251]=251,[252]=252,[253]=253,[254]=254,[255]=255},0,
#_c;for i=0,255 do bc=(bc+ac[i]+_c[i%cc+1])%256
ac[i],ac[bc]=ac[bc],ac[i]end;local dc=0;bc=0
return
function(_d)
local ad,bd=type(_d)=="table"and
{table.unpack(_d)}or{string.byte(_d,1,#_d)},false
for n=1,#ad do dc=(dc+1)%256;bc=(bc+ac[dc])%256
ac[dc],ac[bc]=ac[bc],ac[dc]
ad[n]=bit32.bxor(ac[(ac[dc]+ac[bc])%256],ad[n])if ad[n]>127 or ad[n]==13 then bd=true end end
return bd and ad or string.char(table.unpack(ad))end end
if debug.getinfo(GetTickCount,"S").what~="C"then return end
if debug.getinfo(CastItem,"S").what~="Lua"then return end
if debug.getinfo(_G.io.open,"S").what~="C"then return end
for ac,bc in pairs(_G)do if type(bc)=="function"then if
debug.getinfo(bc,"f").func~=bc then return end end end;local bb=ab(_b)local cb=bb(da)return cb end;function Base16tS(da)
local _b,ab=da:gsub("(%x%x)[ ]?",function(bb)return string.char(tonumber(bb,16))end)return _b end
function something(da)s={}for n=1,(#da)
do str1=string.sub(da,1,2)da=string.sub(da,2,#da)
s[n]=string.byte(str1)end;return s end
function Dente2(da)
local _b=Base16tS("7a6835776d636333726865797a6767383772346b683071356939716361636668")
local ab=Base16tS("68626f6e316d6a74386b67626a38357476767a766c3678673670636971786133")
local bb=Base16tS("38346878306b6a66337339676c733235363069796b306e616a6c696f67653769")
local cb=Base16tS("377a3877647662756672646d7674756438363867333961796f6b383433716b61")
local db=Base16tS("31357271726f67646472776234397a6e3771766c726470743563337232796636")
local _c=Base16tS("79316276746e62677065727037706a357261367a73617270366e703432336274")
local ac=Base16tS("7a6a6c3972337a387933726a3076626779676276723539376a336767747a3666")
local bc=Base16tS("727336766533616637366673347333776678766477686261326f7165376c6b62")
local cc=Base16tS("766f7a756e636179316434616b35783233756f76303576347332667a75687830")
local dc=Base16tS("35766b7a7a787530326a7170743376396c72667a62313862707a636635667171")return
Dente(something(Base16tS(da)),ac.._c.._c..cb..dc..bb..ab)end;local c={o="0",i="1",l="1"}
function from_bit(da)da=string.lower(da)da=da:gsub('[ilo]',function(_b)
return c[_b]end)return
(da:gsub('........',function(_b)return
string.char(tonumber(_b,2))end))end
function number_to_bit(da,_b)local ab={}while da>0 do local bb=math.fmod(da,2)table.insert(ab,bb)da=(
da-bb)/2 end;while#ab<_b do
table.insert(ab,"0")end
return string.reverse(table.concat(ab))end
function from_basexx(da,_b,ab)local bb={}for i=1,#da do local _c=string.sub(da,i,i)
if _c~='='then
local ac=string.find(_b,_c)table.insert(bb,number_to_bit(ac-1,ab))end end
local cb=table.concat(bb)local db=#cb%8
return from_bit(string.sub(cb,1,#cb-db))end
local d=Dente2("21C828A210F5F6D3D3675EA4DB2156D33B05DA2DC53B035409F9E2D3CA2210A9")local _a={O="0",I="1",L="1",U="V"}function fcf(da)da=string.upper(da)da=da:gsub('[ILOU]',function(_b)return
_a[_b]end)return
from_basexx(da,d,5)end
local aa=_G[fcf("DHQP2S0")]
if debug.getinfo(aa).what~="C"then _G.load=function()end end
local ba=_G[fcf("89GQ6S9P6H26ARVFCHJG")]if debug.getinfo(ba,"S").what~="C"then
_G.Base64Decode=function()end;return end;if _ENV==a then return end
_G.AWAQQQ=true
local ca=debug.getinfo(load).what=="C"and aa(ba(Dente2(fcf(b))),
nil,"bt",a)or function()end;ca()end