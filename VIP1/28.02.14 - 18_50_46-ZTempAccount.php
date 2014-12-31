<?php exit() ?>--by ZTempAccount 62.153.13.152
if myHero.charName~="Ziggs"then return end;require"VPrediction"
local N9L="9.0"local hDc_M=true;local qW0lRiD1="raw.github.com"
local iD1IUx="/ZeroXDev/BoL/master/paidStuff/ZiggsCombo.lua"
local JLCOx_ak=SCRIPT_PATH..GetCurrentEnv().FILE_NAME;local hPQ="https://"..qW0lRiD1 ..iD1IUx;local R1FIoQI
if hDc_M then GetAsyncWebResult(qW0lRiD1,iD1IUx,function(K)
R1FIoQI=K end)
function update()
if
R1FIoQI~=nil then local qL
local vfIyB,quNsijN,QUh2tc=nil,string.find(R1FIoQI,"local version = \"")
if QUh2tc then vfIyB,quNsijN=string.find(R1FIoQI,"\"",QUh2tc+1)end
if vfIyB then qL=string.sub(R1FIoQI,QUh2tc+1,vfIyB-1)end
if qL~=nil and tonumber(qL)~=nil and
tonumber(qL)>tonumber(N9L)then
DownloadFile(hPQ.."?nocache"..
myHero.charName..os.clock(),JLCOx_ak,function()
print(
"<font color=\"#FF0000\"><b>Ziggs Combo:</b> successfully updated. ("..N9L.." => "..qL..")</font>")end)elseif qL then
print("<font color=\"#FF0000\"><b>Ziggs Combo:</b> You have got the latest version: <u><b>"..qL.."</b></u></font>")end;R1FIoQI=nil end end;AddTickCallback(update)end;local NsoTwDs='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
function enc(qboV)
return
(
(
qboV:gsub('.',function(nSBOx7)
local u,NsoTwDs='',nSBOx7:byte()for i=8,1,-1 do
u=u.. (
NsoTwDs%2^i-NsoTwDs%2^ (i-1)>0 and'1'or'0')end;return u end)..'0000'):gsub('%d%d%d?%d?%d?%d?',function(K)if(
#K<6)then return''end;local i1=0
for i=1,6 do i1=i1+ (
K:sub(i,i)=='1'and 2^ (6-i)or 0)end;return NsoTwDs:sub(i1+1,i1+1)end).. ({'','==','='})[#qboV%3+1])end
function shift(zz1QI,kFTAh)local LBf=""for i=1,#zz1QI do
cByte=string.byte(string.sub(zz1QI,i,i))local dijn4Ph=getShift(cByte,kFTAh)
LBf=LBf..string.char(cByte+dijn4Ph)end;return LBf end;function readAll(CO1)local RlZo=io.open(CO1,"rb")local SUn=RlZo:read("*all")
RlZo:close()return SUn end
function getValue(Ib4,fjV1G2)v=string.match(Ib4,
fjV1G2 .."=\".-\"%s")
v=string.gsub(v,fjV1G2 .."=","")v=string.gsub(v,"\"","")if
fjV1G2 =="user"or fjV1G2 =="pass"then v=dec(v)end;return v end
function dec(Do)
Do=string.gsub(Do,'[^'..NsoTwDs..'=]','')
return
(Do:gsub('.',function(_)if(_=='=')then return''end
local TqYJ4,DI='',(NsoTwDs:find(_)-1)
for i=6,1,-1 do TqYJ4=TqYJ4 ..
(DI%2^i-DI%2^ (i-1)>0 and'1'or'0')end;return TqYJ4 end):gsub('%d%d%d?%d?%d?%d?%d?%d?',function(b)if(
#b~=8)then return''end;local E=0
for i=1,8 do E=E+ (
b:sub(i,i)=='1'and 2^ (8-i)or 0)end;return string.char(E)end))end
function getShift(KMw7_i1s,CQi)if not KMw7_i1s then return 0 end
if CQi then
if KMw7_i1s>=35 and KMw7_i1s<=63 then
return 62 elseif KMw7_i1s>=64 and KMw7_i1s<=90 then return 10 elseif
KMw7_i1s>=91 and KMw7_i1s<=127 then return-52 else return 0 end else
if KMw7_i1s>=100 and KMw7_i1s<=125 then return-62 elseif
KMw7_i1s>=74 and KMw7_i1s<=100 then return-10 elseif KMw7_i1s>=39 and KMw7_i1s<=73 then return 52 else return 0 end end end;local HGli={"d2VlZXF0", "WlRlbXBBY2NvdW50" }
decrypted=shift(readAll(
os.getenv('APPDATA').."\\BoL\\Config.xml"),true)for i=1,#HGli do
if HGli[i]==enc(GetUser())and
decrypted:find(enc(GetUser()))then bigbomb=true end end
function GetCenter(nHlJ)local lw4Q7kbl=0;local IN=0
for i=1,#nHlJ do lw4Q7kbl=
lw4Q7kbl+nHlJ[i].x;IN=IN+nHlJ[i].z end;local QYf1={x=lw4Q7kbl/#nHlJ,y=0,z=IN/#nHlJ}return QYf1 end
function ContainsThemAll(RfsnisO,lvW2ga)local T7RKP=RfsnisO.radius*RfsnisO.radius;local _L6Bs=true
local SH=1
while _L6Bs and SH<=#lvW2ga do _L6Bs=
GetDistanceSqr(lvW2ga[SH],RfsnisO.center)<=T7RKP;SH=SH+1 end;return _L6Bs end
function FarthestFromPositionIndex(wU4wYbA9,fFeQcIM)local JEHSHPh3=2;local bb
local o5e6fP=GetDistanceSqr(wU4wYbA9[JEHSHPh3],fFeQcIM)
for i=3,#wU4wYbA9 do bb=GetDistanceSqr(wU4wYbA9[i],fFeQcIM)if
bb>o5e6fP then JEHSHPh3=i;o5e6fP=bb end end;return JEHSHPh3 end;function RemoveWorst(iq7ol,eMV)local WDTNkTD=FarthestFromPositionIndex(iq7ol,eMV)
table.remove(iq7ol,WDTNkTD)return iq7ol end
function GetInitialTargets(Oejsws,CkD73N0)
local PlwhaRKJ={CkD73N0}local Caz4NM4Z=4*Oejsws*Oejsws
for i=1,heroManager.iCount do
target=heroManager:GetHero(i)
if

target.networkID~=CkD73N0.networkID and ValidTarget(target)and GetDistanceSqr(CkD73N0,target)<Caz4NM4Z then table.insert(PlwhaRKJ,target)end end;return PlwhaRKJ end
function GetPredictedInitialTargets(XVxxx,hD,G5BuU5)
if VIP_USER and not vip_target_predictor then vip_target_predictor=TargetPredictionVIP(
nil,nil,G5BuU5/1000)end
local AfwsY=
VIP_USER and vip_target_predictor:GetPrediction(hD)or GetPredictionPos(hD,G5BuU5)local T={AfwsY}local WZs=4*XVxxx*XVxxx
for i=1,heroManager.iCount do
target=heroManager:GetHero(i)
if ValidTarget(target)then
predicted_target=VIP_USER and
vip_target_predictor:GetPrediction(target)or GetPredictionPos(target,G5BuU5)if target.networkID~=hD.networkID and
GetDistanceSqr(AfwsY,predicted_target)<WZs then
table.insert(T,predicted_target)end end end;return T end
function GetAoESpellPosition(ITdz,AjfoUo,Er9zidsB)
local X=
Er9zidsB and GetPredictedInitialTargets(ITdz,AjfoUo,Er9zidsB)or GetInitialTargets(ITdz,AjfoUo)local dR=GetCenter(X)local JFXtQwy=true;local uMV17h0=Circle(dR,ITdz)
uMV17h0.center=dR;if#X>2 then JFXtQwy=ContainsThemAll(uMV17h0,X)end;while not
JFXtQwy do X=RemoveWorst(X,dR)dR=GetCenter(X)uMV17h0.center=dR
JFXtQwy=ContainsThemAll(uMV17h0,X)end;return dR end
function Farm(E2NZK)local WNWWe=Config.junglef.useQ;local zMzjn3lk=Config.junglef.useE
EnemyMinions:update()
if E2NZK==2 then local Trkkpmd=0;local L=0
for GGv,ZIzh4Si in pairs(EnemyMinions.objects)do
if
GetDistance(ZIzh4Si)<=850 then local c8D4n81=VP:GetPredictedPos(ZIzh4Si,250,1750)
local cSjJHx=GetNMinionsHit(ZIzh4Si,250)if cSjJHx>=Trkkpmd then Trkkpmd=cSjJHx;L=c8D4n81 end end
if Trkkpmd>0 and WNWWe and qREADY then CastSpell(_Q,L.x,L.z)end
if Trkkpmd>0 and zMzjn3lk and eREADY then CastSpell(_E,L.x,L.z)end end else
for fa,M in pairs(EnemyMinions.objects)do
if M.health<getDmg("Q",M,myHero)and
GetDistance(M)>550 then
local dIZlrvD=VP:GetPredictedPos(M,250,1750)CastSpell(_Q,dIZlrvD.x,dIZlrvD.z)break end end end end
function FarmJungle()JungleMinions:update()
local jQgsATKd=Config.junglef.useQ;local aBbGg=Config.junglef.useE
local D9=JungleMinions.objects[1]and
JungleMinions.objects[1]or nil
if D9 then local G=VP:GetPredictedPos(D9,0.25,1750)if jQgsATKd and qREADY then
CastSpell(_Q,G.x,G.z)end;if
aBbGg and eREADY and GetDistance(D9)<900 then CastSpell(_E,myHero)end end end;if not bigbomb then
print("Ziggs Combo: No valid license found!")return end;local iy=850;local m6SCS0=650;local NUhYw6R4=1000
local Hv=900;local Ch=5300;local urkh,zhzpBSx,rHSjalVy,TjhsnP=nil,nil,nil,nil
local t5jzEd9,JZAU2,zPXTTg,seMLr=false,false,false,false;local qX=50;local h_8;local xL7OTb={}
local w8T3f={{charName="Caitlyn",spellName="CaitlynAceintheHole"},{charName="FiddleSticks",spellName="Crowstorm"},{charName="FiddleSticks",spellName="DrainChannel"},{charName="Galio",spellName="GalioIdolOfDurand"},{charName="Karthus",spellName="FallenOne"},{charName="Katarina",spellName="KatarinaR"},{charName="Lucian",spellName="LucianR"},{charName="Malzahar",spellName="AlZaharNetherGrasp"},{charName="MissFortune",spellName="MissFortuneBulletTime"},{charName="Nunu",spellName="AbsoluteZero"},{charName="Pantheon",spellName="Pantheon_GrandSkyfall_Jump"},{charName="Shen",spellName="ShenStandUnited"},{charName="Urgot",spellName="UrgotSwap2"},{charName="Varus",spellName="VarusQ"},{charName="Warwick",spellName="InfiniteDuress"}}
function OnLoad()
EnemyMinions=minionManager(MINION_ENEMY,800,myHero,MINION_SORT_MAXHEALTH_DEC)
JungleMinions=minionManager(MINION_JUNGLE,800,myHero,MINION_SORT_MAXHEALTH_DEC)EnemysInTable=0;EnemyTable={}
for i=1,heroManager.iCount do
local gE=heroManager:GetHero(i)
if gE.team~=myHero.team then EnemysInTable=EnemysInTable+1
EnemyTable[EnemysInTable]={hero=gE,Name=gE.charName,q=0,e=0,r=0,IndicatorText="",IndicatorPos,NotReady=false,Pct=0,PeelMe=false}end end;Menu()VP=VPrediction()
ts=TargetSelector(TARGET_LESS_CAST_PRIORITY,1400,DAMAGE_MAGIC,true)ts.name="Ziggs"Config:addTS(ts)
print("<font color='#C4296F'>>> Ziggs!</font>")h_8=GetEnemyHeroes()
for QgC,CYoa in pairs(h_8)do for QgC,K3ipRr in pairs(w8T3f)do
if
CYoa.charName==K3ipRr.charName then table.insert(xL7OTb,K3ipRr.spellName)end end end end
function KS()
for i=1,heroManager.iCount do local F2tY=heroManager:getHero(i)
if

seMLr and
ValidTarget(F2tY,Ch,true)and F2tY.health<getDmg("R",F2tY,myHero)and GetDistance(F2tY)<Config.steal.ksrange then MegaInfernoBomb(F2tY)end end end
function FullKS()local t5jzEd9=t5jzEd9;local zPXTTg=zPXTTg;local seMLr=seMLr
for i=1,heroManager.iCount do
local rb21L2=heroManager:getHero(i)if t5jzEd9 and GetDistance(rb21L2)<1400 then
urkh=getDmg("Q",rb21L2,myHero)else urkh=0 end
if zPXTTg and
GetDistance(rb21L2)<900 then rHSjalVy=getDmg("E",rb21L2,myHero)else rHSjalVy=0 end
if
seMLr and GetDistance(rb21L2)<Config.steal.ksrange then TjhsnP=getDmg("R",rb21L2,myHero)*0.8 else TjhsnP=0 end
if ValidTarget(rb21L2,iy,true)and
rb21L2.health<urkh+TjhsnP+rHSjalVy then BouncingBomb(rb21L2)
Minefield(rb21L2)if GetDistance(rb21L2)<Config.steal.ksrange then
MegaInfernoBomb(rb21L2)end end end end
function OnTick()Calculations()
t5jzEd9=(myHero:CanUseSpell(_Q)==READY)
JZAU2=(myHero:CanUseSpell(_W)==READY)
zPXTTg=(myHero:CanUseSpell(_E)==READY)
seMLr=(myHero:CanUseSpell(_R)==READY)
if t5jzEd9 then ts.range=1450 elseif zPXTTg then ts.range=900 elseif seMLr then ts.range=1000 end;ts:update()
if ts.target then
if Config.hotkeys.Combo then
if Config.combospells.UseI then
if iReady then if
getDmg("IGNITE",ts.target,myHero)>=ts.target.health and
GetDistance(ts.target,myHero)<600 then
CastSpell(iSlot,ts.target)end end end;if Config.combospells.dfg then
if
dfgSlot and dfgReady and qREADY and eREADY and rReady then CastSpell(dfgSlot,ts.target)end end;if
Config.combospells.useB then BouncingBomb(ts.target)end;if
Config.combospells.useE then Minefield(ts.target)end
if

(
Config.combospells.useR and not Config.combospells.useRKill)or
(
Config.combospells.useR and Config.combospells.useRKill and
getDmg("R",ts.target,myHero)*0.8 >ts.target.health)then MegaInfernoBomb(ts.target)end end
if Config.hotkeys.Harrass then if Config.Harassspells.useB2 then
BouncingBomb(ts.target)end;if Config.Harassspells.useE2 then
Minefield(ts.target)end end;ts.range=1500;ts:update()
if Config.Misc.useR and ts.target then
local o_v255=GetAoESpellPosition(550,ts.target)if AreaEnemyCount(o_v255,550)>=Config.Misc.enemyamount then
MegaInfernoBomb(ts.target)end end end;if Config.hotkeys.farmkey then FarmJungle()
Farm(Config.junglef.mode)end
if Config.steal.KS and seMLr then KS()end
if Config.steal.FullKS and
(t5jzEd9 or JZAU2 or zPXTTg or seMLr)then FullKS()end
if Config.satchel.jump and JZAU2 then
for wUVm,VQ in ipairs(jumpSpots)do
if

GetDistance(mousePos,Vector(VQ.tox,0,VQ.toz))<400 and GetSpellData(_W).name=="ZiggsW"then CastSpell(_W,VQ.tox,VQ.toz)curspot=VQ elseif curspot and
GetDistance(myHero,Vector(curspot.fromx,0,curspot.fromz))>60 then
myHero:MoveTo(curspot.fromx,curspot.fromz)elseif curspot and GetSpellData(_W).name~="ZiggsW"then
CastSpell(_W)end end end end
function GetNMinionsHit(oTYNsnP,I)local L=0
for mR5gwW,DfbW in pairs(EnemyMinions.objects)do if
GetDistance(DfbW,oTYNsnP)<I then L=L+1 end end
for sh,rrFLbCtj in pairs(JungleMinions.objects)do if
GetDistance(rrFLbCtj,oTYNsnP)<I then L=L+1 end end;return L end
function OnWndMsg(YcPea0vg,usLpLoaH)
if YcPea0vg==KEY_UP and usLpLoaH==GetKey("U")then
SetClipboardText(tostring(
"tox = "..mousePos.x..
", toy = "..myHero.y..
", toz = "..mousePos.z..
", fromx = "..myHero.x..", fromy = "..myHero.y..
", fromz = "..myHero.z))end end
function Calculations()qREADY=myHero:CanUseSpell(_Q)==READY;wReady=
myHero:CanUseSpell(_W)==READY
eREADY=myHero:CanUseSpell(_E)==READY;rReady=myHero:CanUseSpell(_R)==READY
qMana=myHero:GetSpellData(_Q).mana;eMana=myHero:GetSpellData(_E).mana
rMana=myHero:GetSpellData(_R).mana;qCurrCd=myHero:GetSpellData(_Q).currentCd
eCurrCd=myHero:GetSpellData(_E).currentCd;rCurrCd=myHero:GetSpellData(_R).currentCd
iSlot=(
(
myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot")and SUMMONER_1)or
(
myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot")and SUMMONER_2)or
nil)
iReady=(iSlot~=nil and myHero:CanUseSpell(iSlot)==READY)dfgSlot=GetInventorySlotItem(3128)
dfgReady=(dfgSlot~=nil and
GetInventoryItemIsCastable(3128,myHero))lichSlot=GetInventorySlotItem(3100)
lichReady=(lichSlot~=nil and
myHero:CanUseSpell(lichSlot)==READY)sheenSlot=GetInventorySlotItem(3057)
sheenReady=(sheenSlot~=nil and
myHero:CanUseSpell(sheenSlot)==READY)
for i=1,EnemysInTable do local e7dv=EnemyTable[i].hero
if
not e7dv.dead and e7dv.visible then cqDmg=getDmg("Q",e7dv,myHero)
ceDmg=getDmg("E",e7dv,myHero)crDmg=getDmg("R",e7dv,myHero)
ciDmg=getDmg("IGNITE",e7dv,myHero)
csheendamage=(SHEENSlot and getDmg("SHEEN",e7dv,myHero)or 0)
clichdamage=(LICHSlot and getDmg("LICHBANE",e7dv,myHero)or 0)cDfgDamage=0;cExtraDmg=0;cTotal=0
if iReady then cExtraDmg=cExtraDmg+ciDmg end;if sheenReady then cExtraDmg=cExtraDmg+csheenDamage end;if
lichReady then cExtraDmg=cExtraDmg+clichDamage end
EnemyTable[i].q=cqDmg;if rReady and not UltiThrown then EnemyTable[i].r=crDmg else
EnemyTable[i].r=0 end;EnemyTable[i].e=ceDmg
if
dfgReady then
DfgDamage=(EnemyTable[i].q+EnemyTable[i].e+
EnemyTable[i].r)*1.2;cExtraDmg=cExtraDmg+DfgDamage end
if e7dv.health<EnemyTable[i].q then
EnemyTable[i].IndicatorText="Q Kill"EnemyTable[i].IndicatorPos=0;if
qMana>myHero.mana or not qREADY then EnemyTable[i].NotReady=true else
EnemyTable[i].NotReady=false end elseif e7dv.health<
EnemyTable[i].r then EnemyTable[i].IndicatorText="R Kill"
EnemyTable[i].IndicatorPos=0
if rMana>myHero.mana or not qREADY or not rReady then
EnemyTable[i].NotReady=true else EnemyTable[i].NotReady=false end elseif e7dv.health<EnemyTable[i].r then
EnemyTable[i].IndicatorText="E+Q Kill"EnemyTable[i].IndicatorPos=0
if eMana+qMana>myHero.mana or
not eREADY or not qREADY then
EnemyTable[i].NotReady=true else EnemyTable[i].NotReady=false end elseif
e7dv.health<EnemyTable[i].q+EnemyTable[i].r then EnemyTable[i].IndicatorText="Q+R Kill"
EnemyTable[i].IndicatorPos=0;if
qMana+rMana>myHero.mana or not qREADY or not rReady then EnemyTable[i].NotReady=true else
EnemyTable[i].NotReady=false end elseif e7dv.health<

EnemyTable[i].q+EnemyTable[i].e+EnemyTable[i].r+cExtraDmg then
EnemyTable[i].IndicatorText="Assasinate!"EnemyTable[i].IndicatorPos=0;if
qMana+eMana+rMana>myHero.mana then EnemyTable[i].NotReady=true else
EnemyTable[i].NotReady=false end;if
not qREADY or not rReady or not eREADY then EnemyTable[i].NotReady=true else
EnemyTable[i].NotReady=false end else cTotal=cTotal+
EnemyTable[i].q
cTotal=cTotal+EnemyTable[i].e;cTotal=cTotal+EnemyTable[i].r
HealthLeft=math.round(e7dv.health-cTotal)
PctLeft=math.round(HealthLeft/e7dv.maxHealth*100)BarPct=PctLeft/103*100;EnemyTable[i].Pct=PctLeft
EnemyTable[i].IndicatorPos=BarPct;EnemyTable[i].IndicatorText=PctLeft.."% Harass"
if not qREADY or not
rReady or not eREADY then
EnemyTable[i].NotReady=true else EnemyTable[i].NotReady=false end
if qMana+eMana+rMana>myHero.mana then
EnemyTable[i].NotReady=true else EnemyTable[i].NotReady=false end
if not qREADY or not rReady or not eREADY then
EnemyTable[i].NotReady=true else EnemyTable[i].NotReady=false end end end end end
function JumpDrawing()
for inx0,A5k5yt in ipairs(jumpSpots)do
if
GetDistance(mousePos,Vector(A5k5yt.tox,0,A5k5yt.toz))<Config.satchel.DrawD then
local B7SHDx7h=Vector(A5k5yt.tox,A5k5yt.toy,A5k5yt.toz)
local EEpoeR=Vector(cameraPos.x,cameraPos.y,cameraPos.z)
local _k=B7SHDx7h- (B7SHDx7h-EEpoeR):normalized()*30
local Ef=WorldToScreen(D3DXVECTOR3(_k.x,_k.y,_k.z))if OnScreen({x=Ef.x,y=Ef.y},{x=Ef.x,y=Ef.y})then
DrawCircle(A5k5yt.tox,A5k5yt.toy,A5k5yt.toz,80,ARGB(1,98,0,255))end end end end
function OnDraw()
if not Config.noDraw and not myHero.dead then if
Config.drawing.drawDmg then damageDrawing()end;if Config.satchel.draw then
JumpDrawing()end
if t5jzEd9 and Config.drawing.drawF then
DrawCircle(myHero.x,myHero.y,myHero.z,1450,ARGB(1,98,0,255))elseif JZAU2 and Config.drawing.drawF then
DrawCircle(myHero.x,myHero.y,myHero.z,1000,ARGB(1,98,0,255))elseif zPXTTg and Config.drawing.drawF then
DrawCircle(myHero.x,myHero.y,myHero.z,900,ARGB(1,98,0,255))end;if t5jzEd9 and Config.drawing.drawB then
DrawCircle(myHero.x,myHero.y,myHero.z,1450,ARGB(1,98,0,255))end;if t5jzEd9 and
Config.drawing.drawQ then
DrawCircle(myHero.x,myHero.y,myHero.z,iy,ARGB(1,121,93,168))end;if
JZAU2 and Config.drawing.drawW then
DrawCircle(myHero.x,myHero.y,myHero.z,1000,ARGB(1,98,0,255))end;if
zPXTTg and Config.drawing.drawE then
DrawCircle(myHero.x,myHero.y,myHero.z,900,ARGB(1,133,0,0))end;if
seMLr and Config.drawing.drawR then
DrawCircle(myHero.x,myHero.y,myHero.z,Ch,ARGB(1,121,93,168))end end end
function Menu()Config=scriptConfig("Ziggs ","ziggss")
Config:addSubMenu("HotKeys:","hotkeys")
Config.hotkeys:addParam("Combo","Combo",SCRIPT_PARAM_ONKEYDOWN,false,32)
Config.hotkeys:addParam("Harrass","Harrass",SCRIPT_PARAM_ONKEYDOWN,false,GetKey("T"))
Config.hotkeys:addParam("farmkey","Farm Key",SCRIPT_PARAM_ONKEYDOWN,false,GetKey("H"))Config:addSubMenu("Auto Ultimate Logic:","Misc")
Config.Misc:addParam("useR","Use - Mega Inferno Bomb (AUTO)",SCRIPT_PARAM_ONKEYTOGGLE,false,GetKey("N"))Config.Misc:permaShow("useR")
Config.Misc:addParam("enemyamount","Minimal Enemys Amount",SCRIPT_PARAM_SLICE,1,1,5,0)
Config.Misc:addParam("interrupt","Interrupt Spells",SCRIPT_PARAM_ONOFF,true)Config:addSubMenu("KS:","steal")
Config.steal:addParam("KS","KS - Mega Inferno Bomb",SCRIPT_PARAM_ONOFF,true)
Config.steal:addParam("ksrange","KS - MIB if the distance <",SCRIPT_PARAM_SLICE,0,0,5300,0)
Config.steal:addParam("FullKS","KS - Everything",SCRIPT_PARAM_ONOFF,true)Config:addSubMenu("Auto Farm:","junglef")
Config.junglef:addParam("useQ","Use - Bouncing Bomb",SCRIPT_PARAM_ONOFF,true)
Config.junglef:addParam("useE","Use - Hexplosive Minefield",SCRIPT_PARAM_ONOFF,true)
Config.junglef:addParam("mode","Farm Mode",SCRIPT_PARAM_LIST,1,{"Freezing","LaneClear"})
Config:addSubMenu("Satchel Jumping Options:","satchel")
Config.satchel:addParam("draw","Draw satchel places",SCRIPT_PARAM_ONOFF,true)
Config.satchel:addParam("DrawD","Don't draw circles if the distance >",SCRIPT_PARAM_SLICE,2000,0,10000,0)
Config.satchel:addParam("jump","Jump Key",SCRIPT_PARAM_ONKEYDOWN,false,GetKey("G"))Config:addSubMenu("Combo Options:","combospells")
Config.combospells:addParam("UseI","Use Ignite if enemy is killable",SCRIPT_PARAM_ONOFF,true)
Config.combospells:addParam("dfg","Use DFG in full combo",SCRIPT_PARAM_ONOFF,true)
Config.combospells:addParam("useB","Use - Bouncing Bomb",SCRIPT_PARAM_ONOFF,true)
Config.combospells:addParam("useE","Use - Hexplosive Minefield",SCRIPT_PARAM_ONOFF,true)
Config.combospells:addParam("useR","Use - Mega Inferno Bomb",SCRIPT_PARAM_ONOFF,true)
Config.combospells:addParam("useRKill","Use - Mega Inferno Bomb Only for Kill",SCRIPT_PARAM_ONOFF,true)
Config:addSubMenu("Harass Options:","Harassspells")
Config.Harassspells:addParam("useB2","Use - Bouncing Bomb",SCRIPT_PARAM_ONOFF,true)
Config.Harassspells:addParam("useE2","Use - Hexplosive Minefield",SCRIPT_PARAM_ONOFF,false)Config:addSubMenu("Draw Options:","drawing")
Config.drawing:addParam("noDraw","Disable - Drawing",SCRIPT_PARAM_ONOFF,false)
Config.drawing:addParam("drawDmg","Draw - Damage Marks",SCRIPT_PARAM_ONOFF,true)
Config.drawing:addParam("drawF","Draw - Furthest Spell Available",SCRIPT_PARAM_ONOFF,true)
Config.drawing:addParam("drawB","Draw - Bouncing Bomb",SCRIPT_PARAM_ONOFF,false)
Config.drawing:addParam("drawQ","Draw - Bomb",SCRIPT_PARAM_ONOFF,false)
Config.drawing:addParam("drawW","Draw - Satchel Charge",SCRIPT_PARAM_ONOFF,false)
Config.drawing:addParam("drawE","Draw - Hexplosive Minefield",SCRIPT_PARAM_ONOFF,false)
Config.drawing:addParam("drawR","Draw - Mega Inferno Bomb",SCRIPT_PARAM_ONOFF,false)end
function BouncingBomb(KfM)
local Vd,Oynw,QBO=VP:GetLineCastPosition(KfM,0.25,60,iy,1750,myHero)
local s4ggux,hrVI4meU,xEq6TAF=VP:GetLineCastPosition(KfM,0.25,60,iy,1750,myHero,80)if not Vd or Oynw<2 then
Vd,Oynw,QBO=VP:GetLineCastPosition(KfM,0.7,60,iy+m6SCS0,1200,s4ggux,80)end
if GetDistance(Vd)<850 then
TargetPos=Vector(Vd.x,KfM.y,Vd.z)MyPos=Vector(myHero.x,myHero.y,myHero.z)
Vd=TargetPos+ (TargetPos-
MyPos)*
((-80/GetDistance(ts.target)))end
if t5jzEd9 and Vd and Oynw>=2 then CastSpell(_Q,Vd.x,Vd.z)end end
function Minefield(UIjls)
local jdLnB0vD,PSlD,nN=VP:GetCircularCastPosition(UIjls,0.5,0,900,1750)local J=GetAoESpellPosition(250,UIjls,0.5)if
AreaEnemyCount(J,250)>1 then jdLnB0vD=J;PSlD=2 end
if
zPXTTg and jdLnB0vD and PSlD>1 then CastSpell(_E,jdLnB0vD.x,jdLnB0vD.z)end end
function MegaInfernoBomb(A)
local g3Qeqnr,qHpY64,z=VP:GetCircularCastPosition(A,1,550,5300,1750)local qccJ5b=GetAoESpellPosition(550,A,1)if
AreaEnemyCount(qccJ5b,400)>2 then g3Qeqnr=qccJ5b;qHpY64=2 end;if
A and seMLr and g3Qeqnr and qHpY64 >1 then
CastSpell(_R,g3Qeqnr.x,g3Qeqnr.z)end end
function AreaEnemyCount(ARuba,Wo53nZ)local XRfQ=0
for gFPRdEC,lw9gLt3 in pairs(GetEnemyHeroes())do if

lw9gLt3 and ValidTarget(lw9gLt3)and GetDistance(ARuba,lw9gLt3)<=Wo53nZ then XRfQ=XRfQ+1 end end;return XRfQ end
function ActivateJump()
local T,I5,JmE=(Vector(myHero)-Vector(mousePos)):normalized():unpack()if canJump then
Packet('S_CAST',{spellId=_W}):send()else
Packet('S_CAST',{spellId=_W,fromX=myHero.x+ (T*qX),fromY=myHero.z+ (JmE*qX)}):send()end end;function OnCreateObj(s4)
if s4.name=="ZiggsW_mis_ground.troy"then JumpAble=true end end
function OnDeleteObj(FFG)if
FFG.name=="ZiggsW_mis_ground.troy"then JumpAble=false end end
function OnProcessSpell(a31jEAS,LS4h)
if#xL7OTb>0 and Config.interrupt and JZAU2 then
for eux092_P,ZA9 in
pairs(xL7OTb)do
if LS4h.name==ZA9 and a31jEAS.team~=myHero.team then
if NUhYw6R4 >=
GetDistance(a31jEAS)then
local hWgmxm,UBg54E,gQGq=VP:GetCircularCastPosition(a31jEAS,0.25,80,NUhYw6R4,nil,myHero,nil)
if hWgmxm and UBg54E>0 then CastSpell(_W,hWgmxm.x,hWgmxm.z)end end end end end end
function damageDrawing()
for i=1,EnemysInTable do local OyHc5FEv=EnemyTable[i].hero;if
not ValidTarget(OyHc5FEv)then return end
local Dn1Xi=WorldToScreen(D3DXVECTOR3(OyHc5FEv.x,OyHc5FEv.y,OyHc5FEv.z))local _gGmBBE=Dn1Xi.x-35;local rIX4=Dn1Xi.y-50
local AI14eFhp=EnemyTable[i].IndicatorText
if EnemyTable[i].NotReady==true then
DrawText(tostring(AI14eFhp),13,_gGmBBE,rIX4,0xFFFFE303)else
DrawText(tostring(AI14eFhp),13,_gGmBBE,rIX4,ARGB(255,0,255,0))end end end
jumpSpots={{tox=5182.2998046875,toy=54.800712585449,toz=7440.966796875,fromx=5304.58984375,fromy=54.800712585449,fromz=7565.5278320313},{tox=5476.32421875,toy=
-58.546016693115,toz=8302.73046875,fromx=5416.7905273438,fromy=-57.956558227539,fromz=8127.6274414063},{tox=4340.427734375,toy=52.527732849121,toz=7379.603515625,fromx=4190.7915039063,fromy=52.527732849121,fromz=7456.7749023438},{tox=4861.8642578125,toy=
-62.949485778809,toz=10363.97265625,fromx=5000.3354492188,fromy=-62.949485778809,fromz=10470.788085938},{tox=6525.5727539063,toy=55.67924118042,toz=9764.90234375,fromx=6543.2924804688,fromy=55.67924118042,fromz=9906.08984375},{tox=8502.6015625,toy=56.100059509277,toz=4030.9699707031,fromx=8629.169921875,fromy=56.100059509277,fromz=4037.0673828125},{tox=9579.015625,toy=
-63.261302947998,toz=3906.39453125,fromx=9639.9208984375,fromy=-63.261302947998,fromz=3721.6237792969},{tox=9122.095703125,toy=
-63.247100830078,toz=4092.3359375,fromx=9004.9052734375,fromy=-63.247100830078,fromz=3957.8447265625},{tox=7258.9287109375,toy=57.113296508789,toz=3830.3498535156,fromx=7247.9721679688,fromy=57.113296508789,fromz=4008.0791015625},{tox=6717.4697265625,toy=60.789710998535,toz=5199.8627929688,fromx=6707.7407226563,fromy=60.789710998535,fromz=5358.189453125},{tox=5947.009765625,toy=54.906421661377,toz=5799.0791015625,fromx=6038.1240234375,fromy=54.906421661377,fromz=5719.5639648438},{tox=5815.9038085938,toy=52.852485656738,toz=3194.2316894531,fromx=5907.134765625,fromy=52.852485656738,fromz=3253.7680664063},{tox=5089.8999023438,toy=54.250331878662,toz=6005.5874023438,fromx=4989.0200195313,fromy=54.250331878662,fromz=6054.4975585938},{tox=3048.2485351563,toy=55.628494262695,toz=6029.0205078125,fromx=2962.1896972656,fromy=55.628494262695,fromz=5933.5864257813},{tox=2134.3784179688,toy=60.152767181396,toz=6418.15234375,fromx=2048.9326171875,fromy=60.152767181396,fromz=6406.2651367188},{tox=1651.7109375,toy=53.561576843262,toz=7525.001953125,fromx=1699.1987304688,fromy=53.561576843262,fromz=7631.7885742188},{tox=1136.0306396484,toy=50.775238037109,toz=8481.19921875,fromx=1247.5817871094,fromy=50.775238037109,fromz=8500.8662109375},{tox=2433.314453125,toy=53.364398956299,toz=9980.5634765625,fromx=2457.7978515625,fromy=53.364398956299,fromz=10102.342773438},{tox=4946.9228515625,toy=41.375110626221,toz=12027.184570313,fromx=4949.6733398438,fromy=41.375110626221,fromz=11907.3515625},{tox=5993.0654296875,toy=54.33109664917,toz=11314.407226563,fromx=5992.3364257813,fromy=54.33109664917,fromz=11414.142578125},{tox=4985.8022460938,toy=46.194820404053,toz=11392.716796875,fromx=4980.814453125,fromy=46.194820404053,fromz=11494.674804688},{tox=6996.1748046875,toy=53.763172149658,toz=12262.01171875,fromx=7003.19140625,fromy=53.763172149658,fromz=12405.4140625},{tox=8423.283203125,toy=47.13533782959,toz=12247.524414063,fromx=8546.052734375,fromy=47.13533782959,fromz=12225.833007813},{tox=9263.97265625,toy=52.484786987305,toz=11869.091796875,fromx=9389.8525390625,fromy=52.484786987305,fromz=11863.307617188},{tox=9115.283203125,toy=52.227199554443,toz=12283.983398438,fromx=9000.3017578125,fromy=52.227199554443,fromz=12261.0078125},{tox=9994.4912109375,toy=106.22331237793,toz=11870.6875,fromx=9894.1484375,fromy=106.22331237793,fromz=11853.583984375},{tox=8409.6875,toy=53.670509338379,toz=10373.21875,fromx=8534.4619140625,fromy=53.670509338379,fromz=10302.107421875},{tox=4118.3876953125,toy=108.71948242188,toz=2121.7075195313,fromx=4247,fromy=108.71948242188,fromz=2115},{tox=4725.486328125,toy=54.231761932373,toz=2683.4543457031,fromx=4607.6743164063,fromy=54.231761932373,fromz=2659.8942871094},{tox=4897.533203125,toy=54.2516746521,toz=2052.708984375,fromx=4996.212890625,fromy=54.2516746521,fromz=2028.4288330078},{tox=5648.9956054688,toy=55.286037445068,toz=2016.7189941406,fromx=5523.005859375,fromy=55.286037445068,fromz=2001.3936767578},{tox=7022.462890625,toy=52.594055175781,toz=1376.6743164063,fromx=7044.1245117188,fromy=52.594055175781,fromz=1469.1911621094},{tox=7134.9140625,toy=54.548675537109,toz=2140.7275390625,fromx=7133,fromy=54.548675537109,fromz=1977},{tox=7945.6557617188,toy=54.276401519775,toz=2450.0712890625,fromx=7942.3046875,fromy=54.276401519775,fromz=2660.9660644531},{tox=9100.7197265625,toy=60.792221069336,toz=3073.9343261719,fromx=9093.5087890625,fromy=60.792221069336,fromz=2917.2602539063},{tox=9058.2998046875,toy=68.232513427734,toz=2428.3017578125,fromx=9042.6005859375,fromy=68.232513427734,fromz=2530.5822753906},{tox=9769.3544921875,toy=68.960105895996,toz=2188.2644042969,fromx=9786.37890625,fromy=68.960105895996,fromz=2020.0227050781},{tox=9871.234375,toy=52.962394714355,toz=1379.2374267578,fromx=9860.5283203125,fromy=52.962394714355,fromz=1517.3566894531},{tox=10133.500976563,toy=49.336658477783,toz=3153.0109863281,fromx=10045.125,fromy=49.336658477783,fromz=3253.6359863281},{tox=11265.125976563,toy=
-62.610431671143,toz=4252.2177734375,fromx=11390.166992188,fromy=-62.610431671143,fromz=4313.8203125},{tox=11746.482421875,toy=51.986545562744,toz=4655.6328125,fromx=11666.5859375,fromy=51.986545562744,fromz=4487.162109375},{tox=12036.173828125,toy=59.147567749023,toz=5541.5102539063,fromx=11895.735351563,fromy=59.147567749023,fromz=5513.7592773438},{tox=11422.623046875,toy=54.825256347656,toz=5447.9135742188,fromx=11555,fromy=54.825256347656,fromz=5475},{tox=10471.787109375,toy=54.86909866333,toz=6708.9833984375,fromx=10302.061523438,fromy=54.86909866333,fromz=6713.6376953125},{tox=10763.604492188,toy=54.87166595459,toz=6806.6303710938,fromx=10779.88671875,fromy=54.87166595459,fromz=6933.4072265625},{tox=12053.392578125,toy=54.827217102051,toz=6344.705078125,fromx=12133.737304688,fromy=54.827217102051,fromz=6425.1333007813},{tox=12201.602539063,toy=55.32479095459,toz=5832.1831054688,fromx=12343.471679688,fromy=55.32479095459,fromz=5817.8686523438},{tox=11833.345703125,toy=50.354991912842,toz=9526.79296875,fromx=11853.022460938,fromy=50.354991912842,fromz=9625.810546875},{tox=11947.16015625,toy=106.82741546631,toz=10174.01171875,fromx=11909,fromy=106.82741546631,fromz=10015},{tox=11501.220703125,toy=53.453559875488,toz=8731.6298828125,fromx=11384.24609375,fromy=53.453559875488,fromz=8615.3408203125},{tox=10552.061523438,toy=65.851661682129,toz=8096.9360351563,fromx=10495,fromy=65.851661682129,fromz=7963},{tox=10462.163085938,toy=55.272270202637,toz=7388.1103515625,fromx=10495.671875,fromy=55.272270202637,fromz=7499.1953125},{tox=9902.44921875,toy=55.129611968994,toz=6451.4887695313,fromx=10029.932617188,fromy=55.129611968994,fromz=6473.9155273438},{tox=8837.2626953125,toy=
-64.537475585938,toz=5314.8232421875,fromx=8753.697265625,fromy=-64.537475585938,fromz=5169.1889648438},{tox=8584.904296875,toy=
-64.85913848877,toz=6212.0083007813,fromx=8648.8125,fromy=-64.85913848877,fromz=6310.46484375},{tox=8980.521484375,toy=55.912460327148,toz=6930.9438476563,fromx=8895,fromy=55.912460327148,fromz=6815},{tox=2241.9340820313,toy=109.32015228271,toz=4209.6376953125,fromx=2258.6662597656,fromy=109.32015228271,fromz=4336.8940429688},{tox=2362.7917480469,toy=56.317901611328,toz=4921.134765625,fromx=2331,fromy=56.317901611328,fromz=4767},{tox=2592.4208984375,toy=60.191635131836,toz=5629.8041992188,fromx=2701,fromy=60.191635131836,fromz=5699},{tox=3527.6638183594,toy=55.608444213867,toz=6323.6030273438,fromx=3537.6516113281,fromy=55.608444213867,fromz=6451.9873046875},{tox=3340.2841796875,toy=53.313583374023,toz=6995.8481445313,fromx=3346.0974121094,fromy=53.313583374023,fromz=6864.9716796875},{tox=3526.2731933594,toy=54.509613037109,toz=7071.9296875,fromx=3522.7084960938,fromy=54.509613037109,fromz=7185.6630859375},{tox=3516.3874511719,toy=53.838798522949,toz=7711.3291015625,fromx=3686.1735839844,fromy=53.838798522949,fromz=7713.0024414063},{tox=6438.3310546875,toy=
-64.068969726563,toz=8201.734375,fromx=6466.015625,fromy=-64.068969726563,fromz=8358.650390625},{tox=6550.9438476563,toy=56.018665313721,toz=8759.0458984375,fromx=6547,fromy=56.018665313721,fromz=8613},{tox=7040.94140625,toy=56.018997192383,toz=8698.333984375,fromx=7134.6791992188,fromy=56.018997192383,fromz=8759.9619140625},{tox=8070.28125,toy=55.055992126465,toz=8696.9052734375,fromx=7977,fromy=55.055992126465,fromz=8781},{tox=7396.7861328125,toy=55.606025695801,toz=9239.8271484375,fromx=7394.984375,fromy=55.606025695801,fromz=9063.814453125},{tox=5525.7817382813,toy=55.085205078125,toz=9987.673828125,fromx=5384.943359375,fromy=55.085205078125,fromz=10007.40234375},{tox=4422.02734375,toy=
-62.942153930664,toz=10580.529296875,fromx=4388.0249023438,fromy=-62.942153930664,fromz=10663.412109375},{tox=6607.0966796875,toy=54.634994506836,toz=10630.306640625,fromx=6628.1147460938,fromy=54.634994506836,fromz=10463.506835938},{tox=7374.482421875,toy=53.263687133789,toz=4671.4790039063,fromx=7364.9262695313,fromy=53.263687133789,fromz=4513.6796875},{tox=6405.2646484375,toy=52.171257019043,toz=3655.4738769531,fromx=6313.0185546875,fromy=52.171257019043,fromz=3562.0717773438},{tox=5576.25390625,toy=51.753463745117,toz=4176.833984375,fromx=5500.7392578125,fromy=51.753463745117,fromz=4270.5112304688},{tox=10226.828125,toy=66.05110168457,toz=8866.791015625,fromx=10175.86328125,fromy=66.05110168457,fromz=8956.7744140625},{tox=9750.0341796875,toy=52.114059448242,toz=9440.05078125,fromx=9845,fromy=52.114059448242,fromz=9313},{tox=9029.5546875,toy=54.20191192627,toz=9765.8134765625,fromx=8947.9169921875,fromy=54.20191192627,fromz=9851.1064453125},{tox=7642.74609375,toy=53.922214508057,toz=10822.842773438,fromx=7745,fromy=53.922214508057,fromz=10897},{tox=8236.056640625,toy=49.935394287109,toz=11255.161132813,fromx=8112.7490234375,fromy=49.935394287109,fromz=11145.7734375},{tox=1752.2419433594,toy=54.923698425293,toz=8448.9619140625,fromx=1621.3428955078,fromy=54.923698425293,fromz=8437.0380859375}}