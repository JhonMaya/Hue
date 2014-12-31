<?php exit() ?>--by ZTempAccount 62.153.13.14
if myHero.charName~="Ziggs"then return end;require"VPrediction"
local a="1.4"
local wqU76o=SCRIPT_PATH..GetCurrentEnv().FILE_NAME;local LB1Z="https://github.com/ZeroXDev/BoL/blob/master/paidStuff/ZiggsCombo.lua"
local N9L=LIB_PATH.."ZiggsComboTmp.txt"
function newversion()DownloadFile(LB1Z,N9L,UpdateCallback)end
function UpdateCallback()file=io.open(N9L,"rb")
if file~=nil then
content=file:read("*all")file:close()os.remove(N9L)
if content then
tmp,sstart=string.find(content,"local version = \"")
if sstart then send,tmp=string.find(content,"\"",sstart+1)end;if send then
Version=tonumber(string.sub(content,sstart+1,send-1))end
if
(Version~=nil)and
(Version>tonumber(a))and content:find("--EOS--")then file=io.open(wqU76o,"w")
if file then file:write(content)
file:flush()file:close()
PrintChat("<font color=\"#81BEF7\" >Ziggs Combo:</font> <font color=\"#00FF00\">Successfully updated to: v"..
Version.."</font>")else
PrintChat("<font color=\"#81BEF7\" >Ziggs Combo:</font> <font color=\"#FF0000\">Error updating to new version (v"..Version..")</font>")end elseif(Version~=nil)and(Version==tonumber(a))then
PrintChat(
"<font color=\"#81BEF7\" >Ziggs Combo:</font> <font color=\"#00FF00\">No updates found, latest version: v"..Version.." </font>")end end end end;local hDc_M='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
function enc(zPXTTg)
return
(
(
zPXTTg:gsub('.',function(seMLr)
local qX,hDc_M='',seMLr:byte()for i=8,1,-1 do
qX=qX.. (
hDc_M%2^i-hDc_M%2^ (i-1)>0 and'1'or'0')end;return qX end)..'0000'):gsub('%d%d%d?%d?%d?%d?',function(h_8)if(
#h_8 <6)then return''end;local xL7OTb=0;for i=1,6 do
xL7OTb=xL7OTb+ (h_8:sub(i,i)=='1'and
2^ (6-i)or 0)end
return hDc_M:sub(xL7OTb+1,xL7OTb+1)end).. ({'','==','='})[#zPXTTg%3+1])end
function shift(w8T3f,K)local qL=""for i=1,#w8T3f do
cByte=string.byte(string.sub(w8T3f,i,i))local vfIyB=getShift(cByte,K)
qL=qL..string.char(cByte+vfIyB)end;return qL end
function readAll(quNsijN)local QUh2tc=io.open(quNsijN,"rb")
local qboV=QUh2tc:read("*all")QUh2tc:close()return qboV end
function getValue(nSBOx7,u)v=string.match(nSBOx7,u.."=\".-\"%s")v=string.gsub(v,
u.."=","")v=string.gsub(v,"\"","")if
u=="user"or u=="pass"then v=dec(v)end;return v end
function dec(K)K=string.gsub(K,'[^'..hDc_M..'=]','')
return
(K:gsub('.',function(i1)if(i1 ==
'=')then return''end
local zz1QI,kFTAh='',(hDc_M:find(i1)-1)for i=6,1,-1 do
zz1QI=zz1QI.. (
kFTAh%2^i-kFTAh%2^ (i-1)>0 and'1'or'0')end;return zz1QI end):gsub('%d%d%d?%d?%d?%d?%d?%d?',function(LBf)if(
#LBf~=8)then return''end;local dijn4Ph=0;for i=1,8 do
dijn4Ph=dijn4Ph+ (LBf:sub(i,i)=='1'and 2^
(8-i)or 0)end;return string.char(dijn4Ph)end))end
function getShift(CO1,RlZo)if not CO1 then return 0 end
if RlZo then
if CO1 >=35 and CO1 <=63 then return 62 elseif
CO1 >=64 and CO1 <=90 then return 10 elseif CO1 >=91 and CO1 <=127 then return-52 else return 0 end else
if CO1 >=100 and CO1 <=125 then return-62 elseif CO1 >=74 and CO1 <=100 then return-10 elseif CO1 >=39 and
CO1 <=73 then return 52 else return 0 end end end;local qW0lRiD1={"d2VlZXF0", "WlRlbXBBY2NvdW50", "Tmlr", "U29sb2ZpcmU=", "TWVyY3VyaWFsNDk5MQ==","TUFST0M=","eW9rb2tveW8=","REFHUjhPTkU=" }
decrypted=shift(readAll(
os.getenv('APPDATA').."\\BoL\\Config.xml"),true)for i=1,#qW0lRiD1 do
if qW0lRiD1[i]==enc(GetUser())and
decrypted:find(enc(GetUser()))then bigbomb=true end end
function GetCenter(SUn)local Ib4=0;local fjV1G2=0
for i=1,#SUn
do Ib4=Ib4+SUn[i].x;fjV1G2=fjV1G2+SUn[i].z end;local Do={x=Ib4/#SUn,y=0,z=fjV1G2/#SUn}return Do end
function ContainsThemAll(_,TqYJ4)local DI=_.radius*_.radius;local b=true;local E=1
while b and E<=#TqYJ4 do b=
GetDistanceSqr(TqYJ4[E],_.center)<=DI;E=E+1 end;return b end
function FarthestFromPositionIndex(KMw7_i1s,CQi)local nHlJ=2;local lw4Q7kbl
local IN=GetDistanceSqr(KMw7_i1s[nHlJ],CQi)
for i=3,#KMw7_i1s do lw4Q7kbl=GetDistanceSqr(KMw7_i1s[i],CQi)if
lw4Q7kbl>IN then nHlJ=i;IN=lw4Q7kbl end end;return nHlJ end;function RemoveWorst(QYf1,RfsnisO)local lvW2ga=FarthestFromPositionIndex(QYf1,RfsnisO)
table.remove(QYf1,lvW2ga)return QYf1 end
function GetInitialTargets(T7RKP,_L6Bs)
local SH={_L6Bs}local wU4wYbA9=4*T7RKP*T7RKP
for i=1,heroManager.iCount do
target=heroManager:GetHero(i)
if

target.networkID~=_L6Bs.networkID and ValidTarget(target)and GetDistanceSqr(_L6Bs,target)<wU4wYbA9 then table.insert(SH,target)end end;return SH end
function GetPredictedInitialTargets(fFeQcIM,JEHSHPh3,bb)
if VIP_USER and not vip_target_predictor then vip_target_predictor=TargetPredictionVIP(
nil,nil,bb/1000)end
local o5e6fP=
VIP_USER and vip_target_predictor:GetPrediction(JEHSHPh3)or GetPredictionPos(JEHSHPh3,bb)local iq7ol={o5e6fP}local eMV=4*fFeQcIM*fFeQcIM
for i=1,heroManager.iCount do
target=heroManager:GetHero(i)
if ValidTarget(target)then
predicted_target=VIP_USER and
vip_target_predictor:GetPrediction(target)or GetPredictionPos(target,bb)if target.networkID~=JEHSHPh3.networkID and
GetDistanceSqr(o5e6fP,predicted_target)<eMV then
table.insert(iq7ol,predicted_target)end end end;return iq7ol end
function GetAoESpellPosition(WDTNkTD,Oejsws,CkD73N0)
local PlwhaRKJ=
CkD73N0 and GetPredictedInitialTargets(WDTNkTD,Oejsws,CkD73N0)or GetInitialTargets(WDTNkTD,Oejsws)local Caz4NM4Z=GetCenter(PlwhaRKJ)local XVxxx=true
local hD=Circle(Caz4NM4Z,WDTNkTD)hD.center=Caz4NM4Z;if#PlwhaRKJ>2 then
XVxxx=ContainsThemAll(hD,PlwhaRKJ)end;while not XVxxx do
PlwhaRKJ=RemoveWorst(PlwhaRKJ,Caz4NM4Z)Caz4NM4Z=GetCenter(PlwhaRKJ)hD.center=Caz4NM4Z
XVxxx=ContainsThemAll(hD,PlwhaRKJ)end
return Caz4NM4Z end
function Farm(G5BuU5)local AfwsY=Config.junglef.useQ;local T=Config.junglef.useE
EnemyMinions:update()
if G5BuU5 ==2 then local WZs=0;local ITdz=0
for AjfoUo,Er9zidsB in pairs(EnemyMinions.objects)do
if
GetDistance(Er9zidsB)<=850 then local X=VP:GetPredictedPos(Er9zidsB,250,1750)
local dR=GetNMinionsHit(Er9zidsB,250)if dR>=WZs then WZs=dR;ITdz=X end end
if WZs>0 and AfwsY and qREADY then CastSpell(_Q,ITdz.x,ITdz.z)end
if WZs>0 and T and eREADY then CastSpell(_E,ITdz.x,ITdz.z)end end else
for JFXtQwy,uMV17h0 in pairs(EnemyMinions.objects)do
if
uMV17h0.health<getDmg("Q",uMV17h0,myHero)and GetDistance(uMV17h0)>550 then
local E2NZK=VP:GetPredictedPos(uMV17h0,250,1750)CastSpell(_Q,E2NZK.x,E2NZK.z)break end end end end
function FarmJungle()JungleMinions:update()
local WNWWe=Config.junglef.useQ;local zMzjn3lk=Config.junglef.useE
local Trkkpmd=JungleMinions.objects[1]and
JungleMinions.objects[1]or nil
if Trkkpmd then local L=VP:GetPredictedPos(Trkkpmd,0.25,1750)if
WNWWe and qREADY then CastSpell(_Q,L.x,L.z)end
if zMzjn3lk and eREADY and
GetDistance(Trkkpmd)<900 then CastSpell(_E,myHero)end end end;if not bigbomb then
print("Ziggs Combo: No valid license found!")return end;newversion()local iD1IUx=850
local JLCOx_ak=650;local hPQ=1000;local R1FIoQI=900;local NsoTwDs=5300;local HGli,iy,m6SCS0,NUhYw6R4=nil,nil,nil,nil
local Hv,Ch,urkh,zhzpBSx=false,false,false,false;local rHSjalVy=50;local TjhsnP;local t5jzEd9={}
local JZAU2={{charName="Caitlyn",spellName="CaitlynAceintheHole"},{charName="FiddleSticks",spellName="Crowstorm"},{charName="FiddleSticks",spellName="DrainChannel"},{charName="Galio",spellName="GalioIdolOfDurand"},{charName="Karthus",spellName="FallenOne"},{charName="Katarina",spellName="KatarinaR"},{charName="Lucian",spellName="LucianR"},{charName="Malzahar",spellName="AlZaharNetherGrasp"},{charName="MissFortune",spellName="MissFortuneBulletTime"},{charName="Nunu",spellName="AbsoluteZero"},{charName="Pantheon",spellName="Pantheon_GrandSkyfall_Jump"},{charName="Shen",spellName="ShenStandUnited"},{charName="Urgot",spellName="UrgotSwap2"},{charName="Varus",spellName="VarusQ"},{charName="Warwick",spellName="InfiniteDuress"}}
function OnLoad()
EnemyMinions=minionManager(MINION_ENEMY,800,myHero,MINION_SORT_MAXHEALTH_DEC)
JungleMinions=minionManager(MINION_JUNGLE,800,myHero,MINION_SORT_MAXHEALTH_DEC)EnemysInTable=0;EnemyTable={}
for i=1,heroManager.iCount do
local GGv=heroManager:GetHero(i)
if GGv.team~=myHero.team then EnemysInTable=EnemysInTable+1
EnemyTable[EnemysInTable]={hero=GGv,Name=GGv.charName,q=0,e=0,r=0,IndicatorText="",IndicatorPos,NotReady=false,Pct=0,PeelMe=false}end end;Menu()VP=VPrediction()
ts=TargetSelector(TARGET_LESS_CAST_PRIORITY,1400,DAMAGE_MAGIC,true)ts.name="Ziggs"Config:addTS(ts)
print("<font color='#C4296F'>>> Ziggs!</font>")TjhsnP=GetEnemyHeroes()
for ZIzh4Si,c8D4n81 in pairs(TjhsnP)do for ZIzh4Si,cSjJHx in pairs(JZAU2)do
if
c8D4n81.charName==cSjJHx.charName then table.insert(t5jzEd9,cSjJHx.spellName)end end end end
function KS()
for i=1,heroManager.iCount do local fa=heroManager:getHero(i)
if
zhzpBSx and
ValidTarget(fa,NsoTwDs,true)and fa.health<getDmg("R",fa,myHero)+30 then MegaInfernoBomb(fa)end end end
function FullKS()
for i=1,heroManager.iCount do local M=heroManager:getHero(i)if Hv then
HGli=getDmg("Q",M,myHero)else HGli=0 end
if Ch then iy=getDmg("W",M,myHero)else iy=0 end;if urkh then m6SCS0=getDmg("E",M,myHero)else m6SCS0=0 end
if
zhzpBSx then NUhYw6R4=getDmg("R",M,myHero)else NUhYw6R4=0 end;if ValidTarget(M,iD1IUx,true)and
M.health<HGli+NUhYw6R4+m6SCS0+iy then BouncingBomb(M)Minefield(M)
MegaInfernoBomb(M)end end end
function OnTick()Calculations()
Hv=(myHero:CanUseSpell(_Q)==READY)Ch=(myHero:CanUseSpell(_W)==READY)urkh=(
myHero:CanUseSpell(_E)==READY)zhzpBSx=(
myHero:CanUseSpell(_R)==READY)if Hv then ts.range=1450 elseif urkh then
ts.range=900 elseif zhzpBSx then ts.range=1000 end
ts:update()
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
local dIZlrvD=GetAoESpellPosition(550,ts.target)if AreaEnemyCount(dIZlrvD,550)>=Config.Misc.enemyamount then
MegaInfernoBomb(ts.target)end end end;if Config.hotkeys.farmkey then FarmJungle()
Farm(Config.junglef.mode)end
if Config.steal.KS and zhzpBSx then KS()end;if
Config.steal.FullKS and(Hv or Ch or urkh or zhzpBSx)then FullKS()end
if
Config.satchel.jump and Ch then
for jQgsATKd,aBbGg in ipairs(jumpSpots)do
if

GetDistance(mousePos,Vector(aBbGg.tox,0,aBbGg.toz))<400 and GetSpellData(_W).name=="ZiggsW"then CastSpell(_W,aBbGg.tox,aBbGg.toz)curspot=aBbGg elseif curspot and
GetDistance(myHero,Vector(curspot.fromx,0,curspot.fromz))>60 then
myHero:MoveTo(curspot.fromx,curspot.fromz)elseif curspot and GetSpellData(_W).name~="ZiggsW"then
CastSpell(_W)end end end end
function GetNMinionsHit(D9,G)local gE=0;for QgC,CYoa in pairs(EnemyMinions.objects)do if GetDistance(CYoa,D9)<G then gE=
gE+1 end end
for K3ipRr,F2tY in
pairs(JungleMinions.objects)do if GetDistance(F2tY,D9)<G then gE=gE+1 end end;return gE end
function OnWndMsg(rb21L2,o_v255)
if rb21L2 ==KEY_UP and o_v255 ==GetKey("U")then
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
for i=1,EnemysInTable do local wUVm=EnemyTable[i].hero
if
not wUVm.dead and wUVm.visible then cqDmg=getDmg("Q",wUVm,myHero)
ceDmg=getDmg("E",wUVm,myHero)crDmg=getDmg("R",wUVm,myHero)
ciDmg=getDmg("IGNITE",wUVm,myHero)
csheendamage=(SHEENSlot and getDmg("SHEEN",wUVm,myHero)or 0)
clichdamage=(LICHSlot and getDmg("LICHBANE",wUVm,myHero)or 0)cDfgDamage=0;cExtraDmg=0;cTotal=0
if iReady then cExtraDmg=cExtraDmg+ciDmg end;if sheenReady then cExtraDmg=cExtraDmg+csheenDamage end;if
lichReady then cExtraDmg=cExtraDmg+clichDamage end
EnemyTable[i].q=cqDmg;if rReady and not UltiThrown then EnemyTable[i].r=crDmg else
EnemyTable[i].r=0 end;EnemyTable[i].e=ceDmg
if
dfgReady then
DfgDamage=(EnemyTable[i].q+EnemyTable[i].e+
EnemyTable[i].r)*1.2;cExtraDmg=cExtraDmg+DfgDamage end
if wUVm.health<EnemyTable[i].q then
EnemyTable[i].IndicatorText="Q Kill"EnemyTable[i].IndicatorPos=0;if
qMana>myHero.mana or not qREADY then EnemyTable[i].NotReady=true else
EnemyTable[i].NotReady=false end elseif wUVm.health<
EnemyTable[i].r then EnemyTable[i].IndicatorText="R Kill"
EnemyTable[i].IndicatorPos=0
if rMana>myHero.mana or not qREADY or not rReady then
EnemyTable[i].NotReady=true else EnemyTable[i].NotReady=false end elseif wUVm.health<EnemyTable[i].r then
EnemyTable[i].IndicatorText="E+Q Kill"EnemyTable[i].IndicatorPos=0
if eMana+qMana>myHero.mana or
not eREADY or not qREADY then
EnemyTable[i].NotReady=true else EnemyTable[i].NotReady=false end elseif
wUVm.health<EnemyTable[i].q+EnemyTable[i].r then EnemyTable[i].IndicatorText="Q+R Kill"
EnemyTable[i].IndicatorPos=0;if
qMana+rMana>myHero.mana or not qREADY or not rReady then EnemyTable[i].NotReady=true else
EnemyTable[i].NotReady=false end elseif wUVm.health<

EnemyTable[i].q+EnemyTable[i].e+EnemyTable[i].r+cExtraDmg then
EnemyTable[i].IndicatorText="Assasinate!"EnemyTable[i].IndicatorPos=0;if
qMana+eMana+rMana>myHero.mana then EnemyTable[i].NotReady=true else
EnemyTable[i].NotReady=false end;if
not qREADY or not rReady or not eREADY then EnemyTable[i].NotReady=true else
EnemyTable[i].NotReady=false end else cTotal=cTotal+
EnemyTable[i].q
cTotal=cTotal+EnemyTable[i].e;cTotal=cTotal+EnemyTable[i].r
HealthLeft=math.round(wUVm.health-cTotal)
PctLeft=math.round(HealthLeft/wUVm.maxHealth*100)BarPct=PctLeft/103*100;EnemyTable[i].Pct=PctLeft
EnemyTable[i].IndicatorPos=BarPct;EnemyTable[i].IndicatorText=PctLeft.."% Harass"
if not qREADY or not
rReady or not eREADY then
EnemyTable[i].NotReady=true else EnemyTable[i].NotReady=false end
if qMana+eMana+rMana>myHero.mana then
EnemyTable[i].NotReady=true else EnemyTable[i].NotReady=false end
if not qREADY or not rReady or not eREADY then
EnemyTable[i].NotReady=true else EnemyTable[i].NotReady=false end end end end end
function JumpDrawing()
for VQ,oTYNsnP in ipairs(jumpSpots)do
if
GetDistance(mousePos,Vector(oTYNsnP.tox,0,oTYNsnP.toz))<Config.satchel.DrawD then
local I=Vector(oTYNsnP.tox,oTYNsnP.toy,oTYNsnP.toz)
local L=Vector(cameraPos.x,cameraPos.y,cameraPos.z)local mR5gwW=I- (I-L):normalized()*30
local DfbW=WorldToScreen(D3DXVECTOR3(mR5gwW.x,mR5gwW.y,mR5gwW.z))if OnScreen({x=DfbW.x,y=DfbW.y},{x=DfbW.x,y=DfbW.y})then
DrawCircle(oTYNsnP.tox,oTYNsnP.toy,oTYNsnP.toz,80,ARGB(1,98,0,255))end end end end
function OnDraw()
if not Config.noDraw and not myHero.dead then if
Config.drawing.drawDmg then damageDrawing()end;if Config.satchel.draw then
JumpDrawing()end
if Hv and Config.drawing.drawF then
DrawCircle(myHero.x,myHero.y,myHero.z,1450,ARGB(1,98,0,255))elseif Ch and Config.drawing.drawF then
DrawCircle(myHero.x,myHero.y,myHero.z,1000,ARGB(1,98,0,255))elseif urkh and Config.drawing.drawF then
DrawCircle(myHero.x,myHero.y,myHero.z,900,ARGB(1,98,0,255))end;if Hv and Config.drawing.drawB then
DrawCircle(myHero.x,myHero.y,myHero.z,1450,ARGB(1,98,0,255))end;if
Hv and Config.drawing.drawQ then
DrawCircle(myHero.x,myHero.y,myHero.z,iD1IUx,ARGB(1,121,93,168))end;if
Ch and Config.drawing.drawW then
DrawCircle(myHero.x,myHero.y,myHero.z,1000,ARGB(1,98,0,255))end;if
urkh and Config.drawing.drawE then
DrawCircle(myHero.x,myHero.y,myHero.z,900,ARGB(1,133,0,0))end;if
zhzpBSx and Config.drawing.drawR then
DrawCircle(myHero.x,myHero.y,myHero.z,NsoTwDs,ARGB(1,121,93,168))end end end
function Menu()Config=scriptConfig("Ziggs ","ziggss")
Config:addSubMenu("HotKeys:","hotkeys")
Config.hotkeys:addParam("Combo","Combo",SCRIPT_PARAM_ONKEYDOWN,false,32)
Config.hotkeys:addParam("Harrass","Harrass",SCRIPT_PARAM_ONKEYDOWN,false,GetKey("T"))
Config.hotkeys:addParam("farmkey","Farm Key",SCRIPT_PARAM_ONKEYDOWN,false,GetKey("H"))Config:addSubMenu("Auto Ultimate Logic:","Misc")
Config.Misc:addParam("useR","Use - Mega Inferno Bomb",SCRIPT_PARAM_ONKEYTOGGLE,false,GetKey("N"))
Config.Misc:addParam("enemyamount","Minimal Enemys Amount",SCRIPT_PARAM_SLICE,1,1,5,0)
Config.Misc:addParam("interrupt","Interrupt Spells",SCRIPT_PARAM_ONOFF,true)Config:addSubMenu("KS:","steal")
Config.steal:addParam("KS","KS - Mega Inferno Bomb",SCRIPT_PARAM_ONOFF,true)
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
function BouncingBomb(sh)
local rrFLbCtj,YcPea0vg,usLpLoaH=VP:GetLineCastPosition(sh,0.25,60,iD1IUx,1750,myHero)
local e7dv,inx0,A5k5yt=VP:GetLineCastPosition(sh,0.25,60,iD1IUx,1750,myHero,80)if not rrFLbCtj or YcPea0vg<2 then
rrFLbCtj,YcPea0vg,usLpLoaH=VP:GetLineCastPosition(sh,0.7,60,iD1IUx+JLCOx_ak,1200,e7dv,80)end;if
Hv and rrFLbCtj and YcPea0vg>=2 then
CastSpell(_Q,rrFLbCtj.x,rrFLbCtj.z)end end
function Minefield(B7SHDx7h)
local EEpoeR,_k,Ef=VP:GetCircularCastPosition(B7SHDx7h,0.6,80,900)local KfM=GetAoESpellPosition(250,B7SHDx7h,0.5)if
AreaEnemyCount(KfM,250)>1 then EEpoeR=KfM;_k=2 end
if
urkh and EEpoeR and _k>1 then CastSpell(_E,EEpoeR.x,EEpoeR.z)end end
function MegaInfernoBomb(Vd)
local Oynw,QBO,s4ggux=VP:GetCircularCastPosition(Vd,0.25,550,5300,1750,myHero,nil)local hrVI4meU=GetAoESpellPosition(550,Vd,0.5)if
AreaEnemyCount(hrVI4meU,550)>2 then Oynw=hrVI4meU;QBO=2 end;if
Vd and zhzpBSx and Oynw and QBO>1 then
CastSpell(_R,Oynw.x,Oynw.z)end end
function AreaEnemyCount(xEq6TAF,UIjls)local jdLnB0vD=0;for PSlD,nN in pairs(GetEnemyHeroes())do
if nN and ValidTarget(nN)and
GetDistance(xEq6TAF,nN)<=UIjls then jdLnB0vD=jdLnB0vD+1 end end
return jdLnB0vD end
function ActivateJump()
local J,A,g3Qeqnr=(Vector(myHero)-Vector(mousePos)):normalized():unpack()
if canJump then
Packet('S_CAST',{spellId=_W}):send()else
Packet('S_CAST',{spellId=_W,fromX=myHero.x+ (J*rHSjalVy),fromY=myHero.z+ (g3Qeqnr*rHSjalVy)}):send()end end;function OnCreateObj(qHpY64)
if qHpY64.name=="ZiggsW_mis_ground.troy"then JumpAble=true end end
function OnDeleteObj(z)if
z.name=="ZiggsW_mis_ground.troy"then JumpAble=false end end
function OnProcessSpell(qccJ5b,ARuba)
if#t5jzEd9 >0 and Config.interrupt and Ch then
for Wo53nZ,XRfQ in
pairs(t5jzEd9)do
if ARuba.name==XRfQ and qccJ5b.team~=myHero.team then
if hPQ>=
GetDistance(qccJ5b)then
local gFPRdEC,lw9gLt3,T=VP:GetCircularCastPosition(qccJ5b,0.25,80,hPQ,nil,myHero,nil)if gFPRdEC and lw9gLt3 >0 then
CastSpell(_W,gFPRdEC.x,gFPRdEC.z)end end end end end end
function damageDrawing()
for i=1,EnemysInTable do local I5=EnemyTable[i].hero
if not ValidTarget(I5)then return end
local JmE=WorldToScreen(D3DXVECTOR3(I5.x,I5.y,I5.z))local s4=JmE.x-35;local FFG=JmE.y-50
local a31jEAS=EnemyTable[i].IndicatorText;if EnemyTable[i].NotReady==true then
DrawText(tostring(a31jEAS),13,s4,FFG,0xFFFFE303)else
DrawText(tostring(a31jEAS),13,s4,FFG,ARGB(255,0,255,0))end end end
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