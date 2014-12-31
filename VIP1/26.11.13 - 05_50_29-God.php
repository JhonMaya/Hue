<?php exit() ?>--by God 66.25.28.149
------#################################################################################------  ------###########################    Merciless Cassiopeia  ############################------ ------###########################           by Toy         ############################------ ------#################################################################################------

--> Version: Beta Revamped
--> Features:
--> Prodictions in every skill, also taking their hitboxes in consideration.
--> Cast options for Q, W, E and R in both, autocarry and mixed mode (Works separately).
--> Spam Twin Fang only if the enemy is poisoned(like every other Cassiopeia script, derp).
--> Options to use MEC + Prodictions/or only Prodictions for the ultimate, if enabled will only use it if the ultimate will hit "x" number of enemies.
--> Hotkey to use her ultimate with Prodictions, so you can use it situationally even if you set it to only ult in autocarry agains't a big number of opponents
--> KS options to kill with ultimate and Twin Fang(even if the enemy is not poisoned, if it can kill, it shall use Twin Fang).
--> Draw options for every skill, and also a option to draw the furthest skill avaiable.
--> Option to disable auto-attack in autocarry mode.
--> Lag-Free Drawings option into the drawings menu, if you are having FPS issues with this plugin, you can enable it instead of disabling the drawings (If you want to disable it you have to uncheck the option and reload the plugin with F9).
--> KS ignite option.

if myHero.charName ~= "Cassiopeia" then return end

require "Prodiction"
require "AoE_Skillshot_Position"

local qRange = 850
local wRange = 850
local eRange = 700
local rRange = 850

local QAble, WAble, EAble, RAble = false, false, false, false

local Prodict = ProdictManager.GetInstance()
local ProdictQ
local ProdictW
local ProdictR

local autocass = false

function PluginOnLoad()
	AutoCarry.SkillsCrosshair.range = 850
	Menu()
	ProdictQ = Prodict:AddProdictionObject(_Q, qRange, math.huge, 0.535, 80)
	
	ProdictW = Prodict:AddProdictionObject(_W, wRange, math.huge, 0.350, 80)
	
	ProdictR = Prodict:AddProdictionObject(_R, rRange, math.huge, 0.535, 350)
			
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
    igniteslot = SUMMONER_1
  elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
    igniteslot = SUMMONER_2
 end
end

function PluginOnTick()
	Checks()
	FlameOn()
	if Target then
		if Target and (AutoCarry.MainMenu.AutoCarry) then
			Blast()
			Miasma()
			Fang()
			Petrify()
		end
		if Target and (AutoCarry.MainMenu.MixedMode) then
			Blast2()
			Miasma2()
			Fang2()
		end
		if Target and AutoCarry.PluginMenu.KS then KS() end
		if Target and AutoCarry.PluginMenu.KS2 then KS2() end
		if AutoCarry.PluginMenu.useRkey then KeyPetrify() end
	end
	if AutoCarry.PluginMenu.noAttack and AutoCarry.MainMenu.AutoCarry then AutoCarry.CanAttack = false else AutoCarry.CanAttack = true end
	-- Exploit
	if EAble and AutoCarry.PluginMenu.useExploit then
		if Minion and not Minion.type == "obj_Turret" and not Minion.dead and autocass and Minion.health < getDmg("AD", minion, myHero) and GetDistance(Minion, cassaa) <= AutoCarry.PluginMenu.exploitRange then
			CastSpell(_E, minion)
			cassaa = nil
		else 
			for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
				if minion and not minion.dead and autocass and minion.health < getDmg("AD", minion, myHero) and GetDistance(minion, cassaa) <= AutoCarry.PluginMenu.exploitRange then
					CastSpell(_E, minion)
					cassaa = nil
				end
			end
		end
	end
	--Last Hit
	if EAble and AutoCarry.PluginMenu.eFarm and not AutoCarry.MainMenu.AutoCarry then
		--if QAble and AutoCarry.PluginMenu.qFarm and Minion and not Minion.type == "obj_turret" and not Minion.dead and GetDistance(Minion) <= qRange and minion.health < getDmg("Q", minion, myHero) then CastSpell("Q", Minion.x, Minion.z) end
		if Minion and not Minion.type == "obj_Turret" and not Minion.dead and GetDistance(Minion) <= eRange and isPoisoned(Minion) and Minion.health < getDmg("E", Minion, myHero) then 
			CastSpell(_E, Minion)
		else 
			for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
				if minion and not minion.dead and GetDistance(minion) <= eRange and minion.health < getDmg("E", minion, myHero) then 
					CastSpell(_E, minion)
				end
			end
		end
	end
	--Lane Clear	
	if AutoCarry.PluginMenu.Clear and AutoCarry.MainMenu.LaneClear then
		--if QAble and Minion and not Minion.type == "obj_turret" and not Minion.dead and GetDistance(Minion) <= qRange then CastSpell(_Q, Minion.x, Minion.z) end
		--if WAble and Minion and not Minion.type == "obj_turret" and not Minion.dead and GetDistance(Minion) <= wRange then CastSpell(_W, Minion.x, Minion.z) end
		if EAble and Minion and not Minion.type == "obj_Turret" and not Minion.dead and GetDistance(Minion) <= eRange and Minion.health < getDmg("E", Minion, myHero)then 
			CastSpell(_E, Minion)
		else 
			for _, minion in pairs(AutoCarry.EnemyMinions().objects) do
				--if minion and not minion.dead and GetDistance(Minion) <= qRange then CastSpell(_Q, minion.x, minion.z) end
				--if minion and not minion.dead and GetDistance(Minion) <= wRange then CastSpell(_W, minion.x, minion.z) end
				if minion and not minion.dead and GetDistance(minion) <= eRange and minion.health < getDmg("E", minion, myHero) then
					CastSpell(_E, minion)
				end
			end
		end
	end
end

function PluginOnDraw()
    if not myHero.dead then
			if AutoCarry.PluginMenu.EnableLagFree then _G.DrawCircle = DrawCircle2 end
		  if QAble and AutoCarry.PluginMenu.drawF then
      DrawCircle(myHero.x, myHero.y, myHero.z, qRange, 0xFFFFFF)
      else
      if WAble and AutoCarry.PluginMenu.drawF then
      DrawCircle(myHero.x, myHero.y, myHero.z, wRange, 0xFFFFFF)
			else
			if EAble and AutoCarry.PluginMenu.drawF then
      DrawCircle(myHero.x, myHero.y, myHero.z, eRange, 0xFFFFFF)
			else
			if RAble and AutoCarry.PluginMenu.drawF then
      DrawCircle(myHero.x, myHero.y, myHero.z, rRange, 0xFFFFFF)
			else
      end
      end
      if QAble and AutoCarry.PluginMenu.drawQ then
      DrawCircle(myHero.x, myHero.y, myHero.z, qRange, 0xFFFFFF)
		  end
		  if WAble and AutoCarry.PluginMenu.drawW then
      DrawCircle(myHero.x, myHero.y, myHero.z, wRange, 0xFF0000)
		  end
      if EAble and AutoCarry.PluginMenu.drawE then
      DrawCircle(myHero.x, myHero.y, myHero.z, eRange, 0x9933FF)
		  end
      if RAble and AutoCarry.PluginMenu.drawR then
      DrawCircle(myHero.x, myHero.y, myHero.z, rRange, 0x9933FF)
	    end
		end
   end
	end
end

function Menu()
	local HKR = string.byte("A")
	AutoCarry.PluginMenu:addParam("sep", "-- Exploit Options --", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("useExploit", "Stack Passive for Free", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("exploitRange", " Adjust Proj. Distance", SCRIPT_PARAM_SLICE, 100, 0, 500, 0)
	AutoCarry.PluginMenu:addParam("sep1", "-- Misc Options --", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("KS2", "KS - Petrifying Gaze", SCRIPT_PARAM_ONOFF, false)
	AutoCarry.PluginMenu:addParam("KS", "KS - Twin Fang", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("KSIgnite", "KS - Ignite", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("rMEC", "Petrifying Gaze - Use MEC", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("rEnemies", "Petrifying Gaze - Min Enemies",SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
	AutoCarry.PluginMenu:addParam("useRkey", "Hotkey - Petrifying Gaze", SCRIPT_PARAM_ONKEYDOWN, false, HKR)
	AutoCarry.PluginMenu:addParam("sep2", "-- Autocarry Options --", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("noAttack", "Disable Auto-Attack", SCRIPT_PARAM_ONOFF, false)
	AutoCarry.PluginMenu:addParam("sep3", "[Cast]", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("useQ", "Use - Noxious Blast", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useW", "Use - Miasma", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useE", "Use - Twin Fang", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useR", "Use - Petrifying Gaze", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("sep4", "-- Mixed Mode Options --", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("useQ2", "Use - Noxious Blast", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useW2", "Use - Miasma", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("useE2", "Use - Twin Fang", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("sep5", "-- Last Hit Options --", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("eFarm", "Use - Twin Fang", SCRIPT_PARAM_ONOFF, false)
	AutoCarry.PluginMenu:addParam("Clear", "Use skills to LaneClear", SCRIPT_PARAM_ONOFF, false)
	AutoCarry.PluginMenu:addParam("sep6", "-- Drawing Options --", SCRIPT_PARAM_INFO, "")
	AutoCarry.PluginMenu:addParam("EnableLagFree","Enable Lag-Free Drawings", SCRIPT_PARAM_ONOFF, false)
	AutoCarry.PluginMenu:addParam("drawF", "Draw - Furthest Spell Avaiable", SCRIPT_PARAM_ONOFF, true)
	AutoCarry.PluginMenu:addParam("drawQ", "Draw - Noxious Blast", SCRIPT_PARAM_ONOFF, false)
	AutoCarry.PluginMenu:addParam("drawW", "Draw - Miasma", SCRIPT_PARAM_ONOFF, false)
	AutoCarry.PluginMenu:addParam("drawE", "Draw - Twin Fang", SCRIPT_PARAM_ONOFF, false)
	AutoCarry.PluginMenu:addParam("drawR", "Draw - Petrifying Gaze", SCRIPT_PARAM_ONOFF, false)
end

function Checks()
  QAble = (myHero:CanUseSpell(_Q) == READY)
  WAble = (myHero:CanUseSpell(_W) == READY)
  EAble = (myHero:CanUseSpell(_E) == READY)
  RAble = (myHero:CanUseSpell(_R) == READY)
  Target = AutoCarry.GetAttackTarget()
  Minion = AutoCarry.GetMinionTarget()
end

function Blast() 
	if QAble and AutoCarry.PluginMenu.useQ then ProdictQ:GetPredictionCallBack(Target, CastQ) end
end	

function Miasma(target)
  if WAble and AutoCarry.PluginMenu.useW then ProdictW:GetPredictionCallBack(Target, CastW) end
end

function Fang() 
	if isPoisoned(Target) and EAble and AutoCarry.PluginMenu.useE and GetDistance(Target) <= eRange then
	CastSpell(_E, Target) end
end	

function Petrify() 
	if RAble and AutoCarry.PluginMenu.useR then mecR(Target) end
end	

function KeyPetrify() 
	if RAble then ProdictR:GetPredictionCallBack(Target, CastR) end
end	

function Blast2() 
	if QAble and AutoCarry.PluginMenu.useQ2 then ProdictQ:GetPredictionCallBack(Target, CastQ) end
end	

function Miasma2(target)
  if WAble and AutoCarry.PluginMenu.useW2 then ProdictW:GetPredictionCallBack(Target, CastW) end
end

function Fang2() 
	if isPoisoned(Target) and EAble and AutoCarry.PluginMenu.useE2 and GetDistance(Target) <= eRange then
	CastSpell(_E, Target) end
end	

local function getHitBoxRadius(target)
	return GetDistance(target, target.minBBox)
end

function CastQ(unit, pos, spell)
	if GetDistance(pos) - getHitBoxRadius(unit)/2 < qRange then
		CastSpell(_Q, pos.x, pos.z) end
	end
	
function CastW(unit, pos, spell)
	if GetDistance(pos) - getHitBoxRadius(unit)/2 < wRange then
		CastSpell(_W, pos.x, pos.z) end
	end

function CastR(unit, pos, spell)
	if GetDistance(pos) - getHitBoxRadius(unit)/2 < rRange then
		CastSpell(_R, pos.x, pos.z) end
end

--function CastMecR(unit, pos, spell)
--	if GetDistance(pos) - getHitBoxRadius(unit)/2 < rRange then
--		CastSpell(_R, ultPos.x, ultPos.z) end
--end

function KS()
	for i, enemy in ipairs(GetEnemyHeroes()) do
		local eDmg = getDmg("E", enemy, myHero)
		if Target and not Target.dead and Target.health < eDmg and GetDistance(Target) < eRange then
			CastSpell(_E, Target)
		end
	end
end

function KS2()
	for i, enemy in ipairs(GetEnemyHeroes()) do
		local rDmg = getDmg("R", enemy, myHero)
		if Target and not Target.dead and Target.health < rDmg and GetDistance(Target) < rRange then
			ProdictR:GetPredictionCallBack(Target, CastR)
		end
	end
end

function FlameOn( )
    for _, igtarget in pairs(GetEnemyHeroes()) do
                if ValidTarget(igtarget, 600) and KSIgnite and igtarget.health <= 50 + (20 * player.level) then
                CastSpell(igniteslot, igtarget)
        end
    end
end

function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
    radius = radius or 300
  quality = math.max(8,round(180/math.deg((math.asin((chordlength/(2*radius)))))))
  quality = 2 * math.pi / quality
  radius = radius*.92
    local points = {}
    for theta = 0, 2 * math.pi + quality, quality do
        local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
        points[#points + 1] = D3DXVECTOR2(c.x, c.y)
    end
    DrawLines2(points, width or 1, color or 4294967295)
end

function round(num) 
 if num >= 0 then return math.floor(num+.5) else return math.ceil(num-.5) end
end

function DrawCircle2(x, y, z, radius, color)
    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
    if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
        DrawCircleNextLvl(x, y, z, radius, 1, color, 75) 
    end
end

function CountEnemies(point, range)
	local ChampCount = 0
	for j = 1, heroManager.iCount, 1 do
		local enemyhero = heroManager:getHero(j)
		if myHero.team ~= enemyhero.team and ValidTarget(enemyhero, rRange) then
			if GetDistance(enemyhero, point) <= range then
				ChampCount = ChampCount + 1
			end
		end
	end            
	return ChampCount
end
     
function mecR(target)
	if AutoCarry.PluginMenu.rMEC then
		local ultPos = GetAoESpellPosition(350, target)
		if ultPos and GetDistance(ultPos) <= rRange then
			if CountEnemies(ultPos, 350) >= AutoCarry.PluginMenu.rEnemies then
				CastSpell(_R, ultPos.x, ultPos.z)
			end
		end
	elseif GetDistance(target) <= rRange then
		ProdictR:GetPredictionCallBack(Target, CastR)
	end
end

--function AutoCarry.Plugins:RegisterOnAttacked(Minion)
--	if isSACReborn and AutoCarry.PluginMenu.useExploit and EAble and Minion and not Minion.dead and GetDistance(Minion) <= eRange and Minion.health < getDmg("", minion, myHero)  then 
--		CastSpell(_E, Minion.x, Minion.z) end
--	end

function isPoisoned(target)
	for i = 1, target.buffCount, 1 do
		local tBuff = target:getBuff(i)
		if BuffIsValid(tBuff) then
			if tBuff.name:lower():find("poison") then
				return true
			end
		end
	end
	return false
end

function PluginOnCreateObj(obj)
        if obj.name:find("BasicAttack") then
                cassaa = obj
                autocass = true
        end
end
 
function PluginOnDeleteObj(obj)
        if obj.name:find("BasicAttack") then
                cassaa = nil
                autocass = false
        end
end