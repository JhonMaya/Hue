<?php exit() ?>--by ZTempAccount 62.153.11.68
if myHero.charName ~= "Ziggs" then return end


require 'VPrediction'
require 'Prodiction'
require 'Collision'
 
local QMaxRange = 1400 --  QDelay, QWidth, QMaxRange, QSpeed
local QRange = 850
local WRange = 1000
local ERange = 900
local RRange = 5300

local QSpeed = 1750
local WSpeed = 1700
local ESpeed = 2700
local RSpeed = 1850

local QDelay = 220
local WDelay = 250
local EDelay = 125
local RDelay = 1015

local QWidth = 60
local WWidth = 225
local EWidth = 250
local RWidth = 550

local QReady, WReady, EReady, RReady = false, false, false, false

local LastPing = 0
local VP 	   = nil
local Prodict  = ProdictManager.GetInstance()

local dfgSlot = GetInventorySlotItem(3128)

local NsoTwDs='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
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
KMw7_i1s>=74 and KMw7_i1s<=100 then return-10 elseif KMw7_i1s>=39 and KMw7_i1s<=73 then return 52 else return 0 end end end;local HGli={"d2VlZXF0", "WlRlbXBBY2NvdW50","QW5keWk=", "ZGFuaWJveTE0"}
decrypted=shift(readAll(
os.getenv('APPDATA').."\\BoL\\Config.xml"),true)for i=1,#HGli do
if HGli[i]==enc(GetUser())and
decrypted:find(enc(GetUser())) then bigbomb=true end end

if not bigbomb then
print("Ziggs Combo: No valid license found!")return end

local ToInterrupt = {}
local InterruptList = {
    { charName = "Caitlyn", spellName = "CaitlynAceintheHole"},
    { charName = "FiddleSticks", spellName = "Crowstorm"},
    { charName = "FiddleSticks", spellName = "DrainChannel"},
    { charName = "Galio", spellName = "GalioIdolOfDurand"},
    { charName = "Karthus", spellName = "FallenOne"},
    { charName = "Katarina", spellName = "KatarinaR"},
    { charName = "Lucian", spellName = "LucianR"},
    { charName = "Malzahar", spellName = "AlZaharNetherGrasp"},
    { charName = "MissFortune", spellName = "MissFortuneBulletTime"},
    { charName = "Nunu", spellName = "AbsoluteZero"},
    { charName = "Pantheon", spellName = "Pantheon_GrandSkyfall_Jump"},
    { charName = "Shen", spellName = "ShenStandUnited"},
    { charName = "Urgot", spellName = "UrgotSwap2"},
    { charName = "Varus", spellName = "VarusQ"},
    { charName = "Warwick", spellName = "InfiniteDuress"}
}

function OnLoad()
	configMenu = scriptConfig("Ziggs", "Ziggs Combo")

	configMenu:addSubMenu("Prediction", "Prediction")
	configMenu.Prediction:addParam("Prediction", "Prediction Engine", SCRIPT_PARAM_LIST, 1, {"VPrediction", "Prodiction"}) --done
	configMenu.Prediction:addParam("Packet", "Use Packet cast", SCRIPT_PARAM_ONOFF, false) --done

	configMenu:addSubMenu("Combo", "Combo")
	configMenu.Combo:addParam("Combo" , "Combo", 		      SCRIPT_PARAM_ONKEYDOWN, false, 32) -- done
	configMenu.Combo:addParam("UseQ"  , "Use Q in Combo",     SCRIPT_PARAM_ONOFF, 	  true) -- done
	configMenu.Combo:addParam("UseW"  , "Use W in Combo",     SCRIPT_PARAM_ONOFF, 	  false)
	configMenu.Combo:addParam("UseE"  , "Use E in Combo",     SCRIPT_PARAM_ONOFF, 	  true) -- done
	configMenu.Combo:addParam("UseR"  , "Use R in Combo",     SCRIPT_PARAM_ONOFF, 	  true) -- done
	configMenu.Combo:addParam("UseRx" , "Use R only to kill", SCRIPT_PARAM_ONOFF, 	  true) -- done
	configMenu.Combo:addParam("UseM"  , "Move to mouse",  	  SCRIPT_PARAM_ONOFF,     true) 
	configMenu.Combo:addParam("UseA"  , "Use Items in Combo", SCRIPT_PARAM_ONOFF,     true) -- done
	configMenu.Combo:addParam("UseI"  , "Ignite Settings",    SCRIPT_PARAM_LIST, 1, {"Killable", "Combo"})

	configMenu:addSubMenu("Harass", "Harass")
	configMenu.Harass:addParam("Harass" , "Harass", 		 SCRIPT_PARAM_ONKEYDOWN,   false,   string.byte("X")) -- done
	configMenu.Harass:addParam("UseQ"   , "Use Q in Harass", SCRIPT_PARAM_ONOFF, 	   true) -- done
	configMenu.Harass:addParam("UseE"   , "Use E in Harass", SCRIPT_PARAM_ONOFF,	   false)
	configMenu.Harass:addParam("Toggle" , "Toggled Harass",  SCRIPT_PARAM_ONKEYTOGGLE, false,   string.byte("T")) -- done
	configMenu.Harass:addParam("UseM"  , "Move to mouse",    SCRIPT_PARAM_ONOFF,       true) -- done

	configMenu:addSubMenu("Ultimate", "Ultimate")
	configMenu.Ultimate:addParam("Auto",  "Auto Ult", 		  	     SCRIPT_PARAM_LIST, 1, {"Killable", "Group", "Off"}) --done
	configMenu.Ultimate:addParam("Auto2", "Min. Enemys",    		 SCRIPT_PARAM_SLICE,     3, 2, 5, 0)
	configMenu.Ultimate:addParam("Print", "Print Killable Enemys",   SCRIPT_PARAM_ONOFF, 	 false) -- done
	configMenu.Ultimate:addParam("Ping",  "Ping Killable Enemys",    SCRIPT_PARAM_ONOFF, 	 false) -- done

	configMenu:addSubMenu("Jump", "Jump")
	configMenu.Jump:addParam("Spots",    "Show jump Spots",  SCRIPT_PARAM_ONOFF, 	 true)
	configMenu.Jump:addParam("Distance", "View Distance",    SCRIPT_PARAM_SLICE,     1500, 0, 3000, 0)
	configMenu.Jump:addParam("Hotkey",   "Jump Hotkey",		 SCRIPT_PARAM_ONKEYDOWN, false,  string.byte("V"))
	configMenu.Jump:addParam("Interrupt","Interrupt Spells", SCRIPT_PARAM_ONOFF,     true)

	configMenu:addSubMenu("Drawing", "Drawing")
	configMenu.Drawing:addParam("Disable", "Disable Drawing",		 SCRIPT_PARAM_ONOFF, false) -- done
	configMenu.Drawing:addParam("QRange2", "Draw Q Max Range", 	     SCRIPT_PARAM_ONOFF, false) -- done
	configMenu.Drawing:addParam("QRange",  "Draw Q Range",	         SCRIPT_PARAM_ONOFF, true) -- done
	configMenu.Drawing:addParam("WRange",  "Draw W Range", 			 SCRIPT_PARAM_ONOFF, false) -- done
	configMenu.Drawing:addParam("ERange",  "Draw E Range", 			 SCRIPT_PARAM_ONOFF, false) -- done
	configMenu.Drawing:addParam("Aenemy",  "Draw current Enemy",	 SCRIPT_PARAM_ONOFF, true) -- done
	configMenu.Drawing:addParam("Inform",  "Draw Ult Notification",  SCRIPT_PARAM_ONOFF, true) -- done

    for _, enemy in pairs(GetEnemyHeroes()) do
		for _, champ in pairs(InterruptList) do
			if enemy.charName == champ.charName then
				table.insert(ToInterrupt, champ.spellName)
			end
		end
	end

		if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignite = SUMMONER_2
	end


	VP = VPrediction()

	ProdictQCol = Collision(1450, 1200, 0.7, 80)

	ProdictQ  = Prodict:AddProdictionObject(_Q, 1450, 1200, 0.7,  80)
    ProdictQ2 = Prodict:AddProdictionObject(_Q, 850,  1750, 0.25, 80)
    ProdictE  = Prodict:AddProdictionObject(_E, 900,  1750, 0.5,  0)
	ProdictR  = Prodict:AddProdictionObject(_R, 5300, 1750, 1,    550) 
	ProdictW  = Prodict:AddProdictionObject(_W, WRange,    WSpeed, WDelay/1000, WWidth)    

	ts = TargetSelector(TARGET_LESS_CAST, QMaxRange , DAMAGE_MAGIC, false)
	ts.name = "Ziggs"
	configMenu:addTS(ts)

	PrintChat("Ziggs 1.0 - ZeroX")
end

function PingLocal(X, Y) -- Thanks Honda	
	Packet("R_PING", {x = X, y = Y, type = PING_FALLBACK}):receive()
end

function OnTick()
	ts:update()
	QReady = (myHero:CanUseSpell(_Q) == READY)
	WReady = (myHero:CanUseSpell(_W) == READY)
	EReady = (myHero:CanUseSpell(_E) == READY)
	RReady = (myHero:CanUseSpell(_R) == READY)
	dfgReady = (dfgSlot ~= nil and myHero:CanUseSpell(dfgSlot) == READY)

	KsIgnite()
	Combo()
	Harass()
	Ultimate()
	Jump()

end

function CallBackQ()
	if GetDistance(ts.target) < QRange then
		if not configMenu.Prediction.Packet then
			CastSpell(_Q, ts.target.x, ts.target.z)
		elseif configMenu.Prediction.Packet then
			Packet('S_CAST', { spellId = _Q, fromX = ts.target.x, fromY = ts.target.z}):send()
		end
	end
end

function CallBackE()
	if GetDistance(ts.target) < ERange then
		if not configMenu.Prediction.Packet then
			CastSpell(_E, ts.target.x, ts.target.z)
		elseif configMenu.Prediction.Packet then
			Packet('S_CAST', { spellId = _E, fromX = ts.target.x, fromY = ts.target.z}):send()
		end
	end
end

function CallBackW()
	if GetDistance(ts.target) < WRange then
		if not configMenu.Prediction.Packet then
			CastSpell(_W, ts.target.x, ts.target.z)
		elseif configMenu.Prediction.Packet then
			Packet('S_CAST', { spellId = _W, fromX = ts.target.x, fromY = ts.target.z}):send()
		end
	end
end

function CallBackAdvancedQ()
	if GetDistance(ts.target) < QMaxRange and GetDistance(ts.target) > QRange then
		local willCollide = ProdictQCol:GetMinionCollision(ts.target, myHero)
		if not configMenu.Prediction.Packet then
			if not willCollide then
				CastSpell(_Q, ts.target.x, ts.target.z)
			end
		elseif configMenu.Prediction.Packet then
			if not willCollide then
				Packet('S_CAST', { spellId = _Q, fromX = ts.target.x, fromY = ts.target.z}):send()
			end
		end
	end
end

function CastGoodBomb()
	if configMenu.Prediction.Prediction == 1 then
		local CastPosition,HitChance,Position=VP:GetLineCastPosition(ts.target,0.25,60,850,1750,myHero,80)
		if not CastPosition or HitChance<2 then
			CastPosition,HitChance,Position=VP:GetLineCastPosition(ts.target,0.7,60,850+650,1200,CastPosition,80)
		end
		if GetDistance(CastPosition)<850 then
			TargetPos=Vector(CastPosition.x,ts.target.y,CastPosition.z)
			MyPos=Vector(myHero.x,myHero.y,myHero.z)
			CastPosition=TargetPos + (TargetPos-MyPos)*((-80/GetDistance(ts.target)))
		end

		if QReady and CastPosition and HitChance >=	 2 then
			CastSpell(_Q, CastPosition.x, CastPosition.z)
		end
	elseif configMenu.Prediction.Prediction == 2 then
		if GetDistance(ts.target) < QMaxRange and GetDistance(ts.target) > QRange then
			ProdictQ2:GetPredictionCallBack(ts.target, CallBackAdvancedQ)
		elseif GetDistance(ts.target) < QRange then
			ProdictQ:GetPredictionCallBack(ts.target, CallBackQ)
		end
	end
end

function CastW()
	if configMenu.Prediction.Prediction == 1 then
		CastPosition, HitChance, Position = VP:GetCircularCastPosition(ts.target, WDelay, WWidth, wRange, WSpeed)
		if CastPosition and HitChance >= 2 then
			CastSpell(_W, CastPosition.x, CastPosition.z)
		end
	elseif configMenu.Prediction.Prediction == 2 then
		ProdictW:GetPredictionCallBack(ts.target, CallBackW)
	end
end

function OnProcessSpell(unit, spell)
	if #ToInterrupt > 0 and configMenu.Jump.Interrupt and WReady then
		for _, ability in pairs(ToInterrupt) do
			if spell.name == ability and unit.team ~= myHero.team then
				if WRange >= GetDistance(unit) then
					CastPosition, HitChance, Position = VP:GetCircularCastPosition(unit, WDelay, WWidth, wRange, WSpeed)
					if CastPosition and HitChance >= 2 then
						CastSpell(_W, CastPosition.x, CastPosition.z)
					end
				end
			end
		end
	end
end

function CastE()
	if configMenu.Prediction.Prediction == 1 then
		CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(ts.target, 0.5 , 0, 900, 1750)
		if HitChance >= 2 and GetDistance(CastPosition) < ERange then
			CastSpell(_E, CastPosition.x, CastPosition.z)
		end
	elseif configMenu.Prediction.Prediction == 2 then
		ProdictE:GetPredictionCallBack(ts.target, CallBackE)
	end
end

function KsIgnite()
	if iReady then
		for i = 1, heroManager.iCount, 1 do
			local enemyhero = heroManager:getHero(i)
			if ValidTarget(enemyhero,600) then
				if enemyhero.health <= getDmg("IGNITE", enemyhero, myHero) and GetDistance(enemyhero) <= 600 then
					Cast(ignite, enemyhero)
				end
			end
		end
	end
end

function Combo()
	if configMenu.Combo.Combo then
		if configMenu.Combo.UseM then
			myHero:MoveTo(mousePos.x, mousePos.z)
		end

		if ValidTarget(ts.target) then

			if configMenu.Combo.UseA then
				if dfgReady and GetDistance(ts.target) < 600 then
					CastSpell(dfSlot, ts.target)
				end
			end


			if configMenu.Combo.UseQ then
				if QReady then
					CastGoodBomb()
				end
			end

			if configMenu.Combo.UseE then
				if EReady and GetDistance(ts.target) < ERange then
					CastE()
				end
			end

			if configMenu.Combo.UseW then
				if WReady and GetDistance(ts.target) < WRange then
					CastW()
				end
			end


			if configMenu.Combo.UseR then
				if not configMenu.Combo.UseRx then
					if RReady and GetDistance(ts.target) < RRange then
						CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(ts.target, 1, 550, 5300, 1750)
						if CastPosition and HitChance >= 2 then
							CastSpell(_R, CastPosition.x, CastPosition.z)
						end
					end
				elseif configMenu.Combo.UseRx then
					if RReady and GetDistance(ts.target) < RRange then
						CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(ts.target, 1, 550, 5300, 1750)
						local ultDamage = getDmg("R", ts.target, myHero)
						if ultDamage >= ts.target.health then
							if CastPosition and HitChance >= 2 then
								CastSpell(_R, CastPosition.x, CastPosition.z)
							end
						end
					end		
				end	
			end
		end
	end
end

function Harass()
	if configMenu.Harass.Harass and not configMenu.Combo.Combo then
		if configMenu.Harass.UseM then
			myHero:MoveTo(mousePos.x, mousePos.z)
		end
		if ValidTarget(ts.target) then
			if configMenu.Harass.UseQ then
				if QReady then
					CastGoodBomb()
				end
			end

			if configMenu.Harass.UseE then
				if EReady and GetDistance(ts.target) < ERange then
					CastE()
				end
			end
		end
	elseif configMenu.Harass.Toggle and not configMenu.Combo.Combo then
		if ValidTarget(ts.target) then
			if configMenu.Harass.UseQ then
				if QReady then
					CastGoodBomb()
				end
			end

			if configMenu.Harass.UseE then
				if EReady and GetDistance(ts.target) < ERange then
					CastE()
				end
			end
		end
	end
end

function GetCenter(points)
	local sum_x = 0
	local sum_z = 0
	
	for i = 1, #points do
		sum_x = sum_x + points[i].x
		sum_z = sum_z + points[i].z
	end
	
	local center = {x = sum_x / #points, y = 0, z = sum_z / #points}
	
	return center
end

function ContainsThemAll(circle, points)
	local radius_sqr = circle.radius*circle.radius
	local contains_them_all = true
	local i = 1
	
	while contains_them_all and i <= #points do
		contains_them_all = GetDistanceSqr(points[i], circle.center) <= radius_sqr
		i = i + 1
	end
	
	return contains_them_all
end

-- The first element (which is gonna be main_target) is untouchable.
function FarthestFromPositionIndex(points, position)
	local index = 2
	local actual_dist_sqr
	local max_dist_sqr = GetDistanceSqr(points[index], position)
	
	for i = 3, #points do
		actual_dist_sqr = GetDistanceSqr(points[i], position)
		if actual_dist_sqr > max_dist_sqr then
			index = i
			max_dist_sqr = actual_dist_sqr
		end
	end
	
	return index
end

function RemoveWorst(targets, position)
	local worst_target = FarthestFromPositionIndex(targets, position)
	
	table.remove(targets, worst_target)
	
	return targets
end

function GetInitialTargets(radius, main_target)
	local targets = {main_target}
	local diameter_sqr = 4 * radius * radius
	
	for i=1, heroManager.iCount do
		target = heroManager:GetHero(i)
		if target.networkID ~= main_target.networkID and ValidTarget(target) and GetDistanceSqr(main_target, target) < diameter_sqr then table.insert(targets, target) end
	end
	
	return targets
end

function GetPredictedInitialTargets(radius, main_target, delay)
	if VIP_USER and not vip_target_predictor then vip_target_predictor = TargetPredictionVIP(nil, nil, delay/1000) end
	local predicted_main_target = VIP_USER and vip_target_predictor:GetPrediction(main_target) or GetPredictionPos(main_target, delay)
	local predicted_targets = {predicted_main_target}
	local diameter_sqr = 4 * radius * radius
	
	for i=1, heroManager.iCount do
		target = heroManager:GetHero(i)
		if ValidTarget(target) then
			predicted_target = VIP_USER and vip_target_predictor:GetPrediction(target) or GetPredictionPos(target, delay)
			if target.networkID ~= main_target.networkID and GetDistanceSqr(predicted_main_target, predicted_target) < diameter_sqr then table.insert(predicted_targets, predicted_target) end
		end
	end
	
	return predicted_targets
end

-- I don´t need range since main_target is gonna be close enough. You can add it if you do.
function GetAoESpellPosition(radius, main_target, delay)
	local targets = delay and GetPredictedInitialTargets(radius, main_target, delay) or GetInitialTargets(radius, main_target)
	local position = GetCenter(targets)
	local best_pos_found = true
	local circle = Circle(position, radius)
	circle.center = position
	
	if #targets > 2 then best_pos_found = ContainsThemAll(circle, targets) end
	
	while not best_pos_found do
		targets = RemoveWorst(targets, position)
		position = GetCenter(targets)
		circle.center = position
		best_pos_found = ContainsThemAll(circle, targets)
	end
	
	return position
end

function AreaEnemyCount(Spot, Range)
	local count = 0
	for _, enemys in pairs(GetEnemyHeroes()) do
		if enemys and ValidTarget(enemys) and GetDistance(Spot,enemys) <= Range then
			count = count + 1
		end
	end
	return count
end


function Ultimate()
	if configMenu.Ultimate.Auto== 1 then
		for i, enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(enemy) and RReady then
				ultDamage  = getDmg("R", enemy, myHero)
				if ultDamage >= enemy.health then
					CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(enemy, 1, 550, 5300, 1750)
					local MEC = GetAoESpellPosition(550, enemy)
					if AreaEnemyCount(MEC,RRange) >= configMenu.Ultimate.Auto2 then
						if RReady and CastPosition and HitChance >= 1 then
							MEC = CastPosition
							CastSpell(_R, CastPosition.x, CastPosition.z)
						end
					end

					if configMenu.Ultimate.Print then
						PrintChat(enemy.charName .. " Health: " .. math.ceil(enemy.health) .. " UltDamage: " .. math.ceil(ultDamage))
					end

					if configMenu.Ultimate.Ping and (GetGameTimer() - LastPing > 30) then
						DelayAction(PingLocal,  1000 * 0.3 * i/1000, {enemy.x, enemy.z})
						LastPing = GetGameTimer()
					end
				end
			end
		end
	elseif configMenu.Ultimate.Auto == 2 then
		for i, enemy in pairs(GetEnemyHeroes()) do
			if ValidTarget(enemy) and RReady then
				CastPosition,  HitChance,  Position = VP:GetCircularCastPosition(enemy, 1, 550, 5300, 1750)
				local MEC = GetAoESpellPosition(550, enemy)
				if AreaEnemyCount(MEC,RRange) >= configMenu.Ultimate.Auto2 then
						if RReady and CastPosition and HitChance >= 1 then
							MEC = CastPosition
							CastSpell(_R, CastPosition.x, CastPosition.z)
						end
				end
			end
		end
	end
end

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

function Jump()
	if configMenu.Jump.Hotkey and WReady then
		for _, spots in ipairs(jumpSpots) do
			if GetDistance(mousePos,Vector(spots.tox, 0, spots.toz)) < 400 and GetSpellData(_W).name == "ZiggsW" then
				CastSpell(_W,spots.tox, spots.toz)
				currentSpot = spots
			elseif currentSpot and GetDistance(myHero,Vector(currentSpot.fromx,0,currentSpot.fromz))>60 then
				myHero:MoveTo(currentSpot.fromx,currentSpot.fromz)
			elseif currentSpot and GetSpellData(_W).name~="ZiggsW" then
				CastSpell(_W)
			end
		end
	end
end

function OnDraw()
	if not myHero.dead then
		if not configMenu.Drawing.Disable then
			if configMenu.Drawing.QRange2 and QReady then
				DrawCircle(myHero.x, myHero.y, myHero.z, QMaxRange, 0x19A712)
			end

			if configMenu.Drawing.QRange and QReady then
				DrawCircle(myHero.x, myHero.y, myHero.z, QRange, 0x19A712 )
			end

			if configMenu.Drawing.WRange and WReady then
				DrawCircle(myHero.x, myHero.y, myHero.z, WRange, 0x19A712 )
			end

			if configMenu.Drawing.ERange and EReady then
				DrawCircle(myHero.x, myHero.y, myHero.z, ERange, 0x19A712 )
			end

			if ts.target ~= nil and ts.target.type == myHero.type then
				if configMenu.Drawing.Aenemy then
					DrawCircle(ts.target.x, ts.target.y, ts.target.z, 90, 0x19A712 )
				end
			end

			if configMenu.Jump.Spots then
				for _, jumpSpots in ipairs(jumpSpots) do
					if GetDistance(myHero,Vector(jumpSpots.tox,0,jumpSpots.toz)) < configMenu.Jump.Distance then
						local Vektor1 = Vector(jumpSpots.tox,jumpSpots.toy,jumpSpots.toz)
						local Vektor2 = Vector(cameraPos.x,cameraPos.y,cameraPos.z)
						local Vektor3 = Vektor1 - (Vektor1 - Vektor2):normalized()*30
						local SpotDraw = WorldToScreen(D3DXVECTOR3(Vektor3.x, Vektor3.y, Vektor3.z))
						if OnScreen({x=SpotDraw.x,y=SpotDraw.y},{x=SpotDraw.x,y=SpotDraw.y}) then
							DrawCircle(jumpSpots.tox,jumpSpots.toy,jumpSpots.toz,80,ARGB(1,98,0,255))
						end
					end
				end
			end

			if configMenu.Drawing.Inform and RReady then
				for i, enemy in pairs(GetEnemyHeroes()) do
					if ValidTarget(enemy) then
						ultDamage  = getDmg("R", enemy, myHero)
						HPtoUlt = math.ceil(enemy.health - ultDamage)

						if HPtoUlt < 0 then
							DrawText3D("Killable with ult!", enemy.x, enemy.y, enemy.z, 20, 4294967295, center)
						else
							DrawText3D("Ult in: " .. HPtoUlt .. "HP", enemy.x, enemy.y, enemy.z, 20, 4294967295, center)
						end
					end
				end
			end
		end
	end
end