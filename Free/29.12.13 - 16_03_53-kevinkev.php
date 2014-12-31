<?php exit() ?>--by kevinkev 27.253.95.57
local c=math.fmod;local d=math.floor
function rc5(_a)
local aa=type(_a)=="table"and
{table.unpack(_a)}or{string.byte(_a,1,#_a)}
local ba,ca,da={[0]=0,[1]=1,[2]=2,[3]=3,[4]=4,[5]=5,[6]=6,[7]=7,[8]=8,[9]=9,[10]=10,[11]=11,[12]=12,[13]=13,[14]=14,[15]=15,[16]=16,[17]=17,[18]=18,[19]=19,[20]=20,[21]=21,[22]=22,[23]=23,[24]=24,[25]=25,[26]=26,[27]=27,[28]=28,[29]=29,[30]=30,[31]=31,[32]=32,[33]=33,[34]=34,[35]=35,[36]=36,[37]=37,[38]=38,[39]=39,[40]=40,[41]=41,[42]=42,[43]=43,[44]=44,[45]=45,[46]=46,[47]=47,[48]=48,[49]=49,[50]=50,[51]=51,[52]=52,[53]=53,[54]=54,[55]=55,[56]=56,[57]=57,[58]=58,[59]=59,[60]=60,[61]=61,[62]=62,[63]=63,[64]=64,[65]=65,[66]=66,[67]=67,[68]=68,[69]=69,[70]=70,[71]=71,[72]=72,[73]=73,[74]=74,[75]=75,[76]=76,[77]=77,[78]=78,[79]=79,[80]=80,[81]=81,[82]=82,[83]=83,[84]=84,[85]=85,[86]=86,[87]=87,[88]=88,[89]=89,[90]=90,[91]=91,[92]=92,[93]=93,[94]=94,[95]=95,[96]=96,[97]=97,[98]=98,[99]=99,[100]=100,[101]=101,[102]=102,[103]=103,[104]=104,[105]=105,[106]=106,[107]=107,[108]=108,[109]=109,[110]=110,[111]=111,[112]=112,[113]=113,[114]=114,[115]=115,[116]=116,[117]=117,[118]=118,[119]=119,[120]=120,[121]=121,[122]=122,[123]=123,[124]=124,[125]=125,[126]=126,[127]=127,[128]=128,[129]=129,[130]=130,[131]=131,[132]=132,[133]=133,[134]=134,[135]=135,[136]=136,[137]=137,[138]=138,[139]=139,[140]=140,[141]=141,[142]=142,[143]=143,[144]=144,[145]=145,[146]=146,[147]=147,[148]=148,[149]=149,[150]=150,[151]=151,[152]=152,[153]=153,[154]=154,[155]=155,[156]=156,[157]=157,[158]=158,[159]=159,[160]=160,[161]=161,[162]=162,[163]=163,[164]=164,[165]=165,[166]=166,[167]=167,[168]=168,[169]=169,[170]=170,[171]=171,[172]=172,[173]=173,[174]=174,[175]=175,[176]=176,[177]=177,[178]=178,[179]=179,[180]=180,[181]=181,[182]=182,[183]=183,[184]=184,[185]=185,[186]=186,[187]=187,[188]=188,[189]=189,[190]=190,[191]=191,[192]=192,[193]=193,[194]=194,[195]=195,[196]=196,[197]=197,[198]=198,[199]=199,[200]=200,[201]=201,[202]=202,[203]=203,[204]=204,[205]=205,[206]=206,[207]=207,[208]=208,[209]=209,[210]=210,[211]=211,[212]=212,[213]=213,[214]=214,[215]=215,[216]=216,[217]=217,[218]=218,[219]=219,[220]=220,[221]=221,[222]=222,[223]=223,[224]=224,[225]=225,[226]=226,[227]=227,[228]=228,[229]=229,[230]=230,[231]=231,[232]=232,[233]=233,[234]=234,[235]=235,[236]=236,[237]=237,[238]=238,[239]=239,[240]=240,[241]=241,[242]=242,[243]=243,[244]=244,[245]=245,[246]=246,[247]=247,[248]=248,[249]=249,[250]=250,[251]=251,[252]=252,[253]=253,[254]=254,[255]=255},0,
#aa;for i=0,255 do ca=(ca+ba[i]+aa[i%da+1])%256
ba[i],ba[ca]=ba[ca],ba[i]end;local _b=0;ca=0
return
function(ab)
local bb,cb=type(ab)=="table"and
{table.unpack(ab)}or{string.byte(ab,1,#ab)},false
for n=1,#bb do _b=(_b+1)%256;ca=(ca+ba[_b])%256
ba[_b],ba[ca]=ba[ca],ba[_b]
bb[n]=bit32.bxor(ba[(ba[_b]+ba[ca])%256],bb[n])if bb[n]>127 or bb[n]==13 then cb=true end end
return cb and bb or string.char(table.unpack(bb))end end
_G.wm864y2ae=function(_a,aa)local ba="Error: Tampering detected. ["
if tonumber==nil or
tonumber("223")~=223 or-9 ~="-10"+1 then return end
if tostring==nil or tostring(220)~="220"then return end;if
string.sub==nil or string.sub("imahacker",4)~="hacker"then return end;if
math==nil or math.random==nil then return end
if
(math.random(math.random(1001))==
math.random(math.random(1002)))and(math.random(math.random(1003))==
math.random(math.random(1004)))then return end;test1,test2=math.random(100,1000),GetLoLPath()function test3()
test4=test1 ..test2 end;if
type==nil or type(test1)~="number"or
type(test2)~="string"or type(test3)~="function"then return end
test1=math.random(1000,10000)test2=tostring(test1)if
tonumber==nil or test1 ~=tonumber(test2)then return end;test1="abaBaCa"test2="ababaca"if
test1:lower()~=test2 then return end
test1=tostring(math.random(1000,9999))test2=tostring(math.random(10000,99999))
test3=tostring(math.random(100,999))
if
string.sub(test1 ..test2 ..test3,string.len(test1)+1)~=test2 ..test3 then return end;if
string.sub(test1 ..test2 ..test3,
string.len(test1)+string.len(test2)+1)~=test3 then return end;local ca=tostring;function debuggetinfo2()
print(1)end;if
debug.getinfo(tostring,"S").what~="C"then print(ba.."d1]")return end
_G.tostring=debuggetinfo2;if debug.getinfo(tostring,"S").what~="Lua"then
print(ba.."d2]")return end;_G.tostring=ca
if
debug.getinfo(tostring,"S").what~="C"then print(ba.."d3]")return end;nC=0
for ac,bc in pairs(_G)do
if math.random(1,10)==3 then if
debug.getinfo(GetTickCount,"S").what~="C"then return end end
if math.random(1,10)==5 then if
debug.getinfo(CastItem,"S").what~="Lua"then return end end
if type(bc)=="function"then if debug.getinfo(bc,"f").func~=bc then
return end;if
debug.getinfo(bc,"S").what=="C"then nC=nC+1 end end end;if nC<148 -12 or nC>148 +12 then return end;if


_G.GetUser==nil or type(_G.GetUser)~="function"or debug==nil or debug.getinfo==nil or
type(_G.GetUser)~="function"then return end
if
debug.getinfo(_G.GetUser,"S")~=nil and
debug.getinfo(_G.GetUser,"S").what~="C"then print(ba.."c0]")return end;if
debug.getinfo(_G.AddTickCallback,"S").what~="C"then print(ba.."c1]")return end
if
debug.getinfo(_G.GetAsyncWebResult,"S").what~="C"then print(ba.."c2]")return end;if
debug.getinfo(_G.DownloadFile,"S").what~="C"then print(ba.."c3]")return end
if
debug.getinfo(_G.os.exit,"S").what~="C"then print(ba.."c4]")return end;if debug.getinfo(_G.io.open,"S").what~="C"then print(ba..
"c5]")return end
if
debug.getinfo(_G.load,"S").what~="C"then print(ba.."c6]")return end;if
debug.getinfo(_G.Base64Decode,"S").what~="C"then print(ba.."c7]")return end
if
debug.getinfo(_G.string.lower,"S").what~="C"then print(ba.."c8]")return end;if
debug.getinfo(_G.GetWebResult,"S").what~="C"then print(ba.."c9]")return end
dif1=
tonumber(string.sub(tostring(debug.getinfo),11),16)-
tonumber(string.sub(tostring(debug.traceback),11),16)
if dif1 <-15000 or dif1 >15000 then print(ba.."a1]")return end
dif2=
tonumber(string.sub(tostring(math.random),11),16)-tonumber(string.sub(tostring(math.max),11),16)
if dif2 <-15000 or dif2 >15000 then print(ba.."a2]")return end
dif3=tonumber(string.sub(tostring(tostring),11),16)-
tonumber(string.sub(tostring(tonumber),11),16)
if dif3 <-15000 or dif3 >15000 then print(ba.."a3]")return end
dif3=
tonumber(string.sub(tostring(string.sub),11),16)-
tonumber(string.sub(tostring(string.len),11),16)
if dif3 <-15000 or dif3 >15000 then print(ba.."a4]")return end
dif4=
tonumber(string.sub(tostring(debug.getinfo),11),16)-
tonumber(string.sub(tostring(debug.traceback),11),16)
if dif4 <-15000 or dif4 >15000 then print(ba.."a5]")return end
dif5=tonumber(string.sub(tostring(os.exit),11),16)-
tonumber(string.sub(tostring(os.time),11),16)
if dif5 <-15000 or dif5 >15000 then print(ba.."a6]")return end;success,all=0.0,0.0
for ac,bc in pairs(_G)do
if type(bc)=="function"then
dif=tonumber(string.sub(tostring(bc),11),16)
if type(dif)~="number"then print(ba.."b1]")return end;all=all+1 end end;if all<=300 then os.exit()end
local da=os.getenv("APPDATA").."\\bol"..
tostring(math.random(1000000,9999999))local _b=io.open(da,"wb")_b:write(aa)_b:close()local ab={}
_b=assert(io.open(da,"rb"))while true do local ac=_b:read(2)
if ac==nil then break else ab[#ab+1]=tonumber(ac,16)end end;_b:close()if FileExist(da)then
os.remove(da)end
local bb=rc5("xk2e3hp0ixvab2n16vorykelh0ouk5vghhcgvot0ea42bsu61jr9s6edo8ysby4r")local cb=bb(ab)
local db=math.random(100,999).."script"..math.random(10,99)local _c=load(Base64Decode(cb),db,nil,_a)_c()_b=nil;da=nil
bb=nil;cb=nil;db=nil;_c=nil end