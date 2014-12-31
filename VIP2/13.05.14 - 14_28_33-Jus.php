<?php exit() ?>--by Jus 189.69.22.95
if myHero.charName ~= "Lissandra" or not VIP_USER then return end

local version 				= "1.0"

local function _AutoupdaterMsg(msg) 
    print("<font color=\"#6699ff\"><b>Lissandra, Frozen Armageddon:</b></font> <font color=\"#FFFFFF\">"..msg..".</font>") 
end

local AUTOUPDATE 			= true
local UPDATE_HOST           = "raw.github.com"
local UPDATE_PATH           = "/Jusbol/scripts/master/SimpleLissandraRelease.lua".."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH      = SCRIPT_PATH.."SimpleLissandraRelease.lua"
local UPDATE_URL            = "https://"..UPDATE_HOST..UPDATE_PATH
if AUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, "/Jusbol/scripts/master/VersionFiles/Lissandra.version")
    if ServerData then
        ServerVersion = type(tonumber(ServerData)) == "number" and tonumber(ServerData) or nil
        if ServerVersion then
            if tonumber(version) < ServerVersion then
                _AutoupdaterMsg("New version available"..ServerVersion)
                _AutoupdaterMsg("Updating from github.com, please don't press F9")
                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () _AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version.") end) end, 3)
            else
               _AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
            end
        end
    else
        _AutoupdaterMsg("Error downloading version info! (github.com)")
    end
end
--[[honda update function]]


require "VPrediction"

-----------------
local myPlayer		=	GetMyHero()
local ts, Target 	=	nil, nil
local vp 			=	nil
-----------------

local IgniteSpell		=	{spellSlot = "SummonerDot", slot = nil, range = 600, ready = false}
local BarreiraSpell		=	{spellSlot = "SummonerBarrier", slot = nil, range = 0, ready = false}
local SmiteSpell		=	{spellSlot = "Smite", slot = nil, range = 0, ready = false}
local function Spell()
	local summonerTable	=	{SUMMONER_1, SUMMONER_2}
	local spells_		=	{IgniteSpell, BarreiraSpell, SmiteSpell}
	for i=1, #summonerTable do
		for a=1, #spells_ do
			if myPlayer:GetSpellData(summonerTable[i]).name:find(spells_[a].spellSlot) then 
				spells_[a].slot = summonerTable[i]
			end
		end
	end
end

local function MenuCombo()
	menu:addSubMenu("[Combo System]", "combo")
	menu.combo:addParam("q", "Use (Q) in Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo:addParam("w", "Use (W) in Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo:addParam("e", "Use (E) in Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo:addParam("r", "Use (R) in Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo:addParam("", "", SCRIPT_PARAM_INFO, "")
	menu.combo:addParam("key", "Combo/Team Fight", SCRIPT_PARAM_ONKEYDOWN, false, 32)
end

local function MenuComboUltimate()
	menu.combo:addSubMenu("[Extra Settings]", "ult")
	menu.combo.ult:addParam("", "[Ultimate Settings]", SCRIPT_PARAM_INFO, "")
	menu.combo.ult:addParam("", "", SCRIPT_PARAM_INFO, "")
	menu.combo.ult:addParam("", "[Self Cast Settings]", SCRIPT_PARAM_INFO, "")
	menu.combo.ult:addParam("self", "Self Cast (R) if health <%", SCRIPT_PARAM_SLICE, 30, 10, 90, 0)
	menu.combo.ult:addParam("number", "Cast (R) if # enemys >=", SCRIPT_PARAM_SLICE, 1, 1, 4, 0)
	menu.combo.ult:addParam("", "[Target Cast Settings]", SCRIPT_PARAM_INFO, "")
	menu.combo.ult:addParam("enemy", "Cast (R) target health <%", SCRIPT_PARAM_SLICE, 50, 10, 100, 0)
	menu.combo.ult:addParam("number2", "Cast (R) if # enemys >=", SCRIPT_PARAM_SLICE, 1, 1, 4, 0)
	menu.combo.ult:addParam("", "", SCRIPT_PARAM_INFO, "")
	menu.combo.ult:addParam("", "[Others Settings]", SCRIPT_PARAM_INFO, "")
	menu.combo.ult:addParam("wN", "Only (W) with # enemys >=", SCRIPT_PARAM_SLICE, 1, 1, 4, 0)
	menu.combo.ult:addParam("gap", "First (E) if # enemys >=", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
	menu.combo.ult:addParam("gap1", "Second (E) if # enemys <=", SCRIPT_PARAM_SLICE, 2, 2, 5, 0)
	menu.combo.ult:addParam("gap2", "(E) range to second cast", SCRIPT_PARAM_SLICE, 340, 100, 700, 0)
	menu.combo.ult:addParam("protc", "(E) Turret Protection", SCRIPT_PARAM_ONOFF, true)
end

local function MenuHarass()
	menu:addSubMenu("[Harass System]", "harass")
	menu.harass:addParam("q", "Auto (Q)", SCRIPT_PARAM_ONOFF, true)	
	menu.harass:addParam("w", "Auto (W)", SCRIPT_PARAM_ONOFF, false)
	menu.harass:addParam("e", "Auto (E)", SCRIPT_PARAM_ONOFF, false)
	menu.harass:addParam("", "", SCRIPT_PARAM_INFO, "")
	menu.harass:addParam("key", "Toggle Harass", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte("Z"))
	menu.harass:addParam("key2", "Harras with Orbwalk", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
	menu.harass:addParam("stop", "Stop if mana % <", SCRIPT_PARAM_SLICE, 30, 0, 90, 0)	
	menu.harass:addParam("wNN", "Auto (W) if # enemys >=", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
	menu.harass:addParam("recallp", "Do not Harass if Recall", SCRIPT_PARAM_ONOFF, true)
	menu.harass:addParam("onlyp", "Only Harass with Passive", SCRIPT_PARAM_ONOFF, true)
end

local function MenuFarm()
	menu:addSubMenu("[Farm System]", "farm")
	menu.farm:addParam("", "[Lane Clear]", SCRIPT_PARAM_INFO, "")
	menu.farm:addParam("q", "Use (Q) in lane clear", SCRIPT_PARAM_ONOFF, true)
	menu.farm:addParam("w", "Use (W) in lane clear", SCRIPT_PARAM_ONOFF, false)
	menu.farm:addParam("e", "Use (E) in lane clear", SCRIPT_PARAM_ONOFF, false)
	menu.farm:addParam("", "", SCRIPT_PARAM_INFO, "")
	menu.farm:addParam("key", "Lane Clear Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
end

local function MenuExtra()
	menu:addSubMenu("[Extra Settings]", "extra")
	menu.extra:addParam("inv", "[Invetory Settings]", SCRIPT_PARAM_ONOFF, true)
	menu.extra:addParam("hp", "HP potion if health <%", SCRIPT_PARAM_SLICE, 60, 0, 90, 0)
	menu.extra:addParam("mana", "Mana potion if mana <%", SCRIPT_PARAM_SLICE, 40, 0, 90, 0)
	menu.extra:addParam("spel", "[Spells Settings]", SCRIPT_PARAM_ONOFF, true)
	menu.extra:addParam("barrier", "Auto Barrier if health <%", SCRIPT_PARAM_SLICE, 20, 0, 90, 0)
	menu.extra:addParam("ignite", "Use Smart Ignite", SCRIPT_PARAM_ONOFF, true)
	--menu.extra:addParam("smartSmite", "Use Smite to steal", SCRIPT_PARAM_ONOFF, true)
end

local function MenuExtraItems()
	menu.extra:addSubMenu("[Others Settings]", "items")
	menu.extra.items:addParam("useitems", "[Items Settings]", SCRIPT_PARAM_ONOFF, true)
	menu.extra.items:addParam("dfg", "Use DFG in Combo", SCRIPT_PARAM_ONOFF, true)	
	menu.extra.items:addParam("zhonia", "Zhonias if health <%", SCRIPT_PARAM_SLICE, 40, 0, 90, 0)
	menu.extra.items:addParam("seraph", "Seraph's Embrace if health <%", SCRIPT_PARAM_SLICE, 50, 0, 90, 0)
end

local function MenuDraw()
	menu:addSubMenu("[Draw System]", "draw")
	menu.draw:addParam("q", "Draw (Q) range", SCRIPT_PARAM_ONOFF, true)
	menu.draw:addParam("w", "Draw (W) range", SCRIPT_PARAM_ONOFF, false)
	menu.draw:addParam("e", "Draw (E) range", SCRIPT_PARAM_ONOFF, true)
	menu.draw:addParam("r", "Draw (R) range", SCRIPT_PARAM_ONOFF, true)	
	menu.draw:addParam("", "", SCRIPT_PARAM_INFO, "")
	menu.draw:addParam("target", "Draw Target", SCRIPT_PARAM_ONOFF, true)
end

local function MenuSystem()
	menu:addSubMenu("[System]", "system")
	menu.system:addParam("walk", "Use Jus Orbwalk", SCRIPT_PARAM_ONOFF, true)
	menu.system:addParam("vpred", "Use VPrediction", SCRIPT_PARAM_ONOFF, true)
end

local function MenuSystemVPred()
	menu.system:addSubMenu("[Hit Chance Settings]", "hit")
	menu.system.hit:addParam("q", "(Q) Hit Chance", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
	menu.system.hit:addParam("e", "(E) Hit Chance", SCRIPT_PARAM_SLICE, 2, 0, 5, 0)
end

local function TargetVariable()
	ts = TargetSelector(TARGET_LESS_CAST, 1200, DAMAGE_MAGIC)
	ts.name = "Lissandra mode:"
	menu:addTS(ts)
end

local function MenuPerma()
	menu.harass:permaShow("key")
end

function OnLoad()
	Spell()
	menu = scriptConfig("Lissandra by Jus", "LissJus")
	MenuCombo()
	MenuComboUltimate()
	MenuHarass()
	MenuFarm()
	MenuExtra()
	MenuExtraItems()
	MenuDraw()
	MenuSystem()
	MenuSystemVPred()
	TargetVariable()
	MenuPerma()
	vp = VPrediction()
	PrintChat("<font color=\"#6699ff\"><b>Lissandra, Frozen Armageddon</b></font>")
end

-----orb-----
local lastAttack, lastWindUpTime, lastAttackCD	=	0,0,0
-------------


local function heroCanMove()
    return ( GetTickCount() + GetLatency() / 2 > lastAttack + lastWindUpTime + 20)
end 
 
local function timeToShoot()
    return (GetTickCount() + GetLatency() / 2 > lastAttack + lastAttackCD)
end 

local function moveToCursor() 
	if GetDistance(mousePos) >= 250 then
 		local moveToPos = myPlayer + (Vector(mousePos) - myPlayer):normalized()* 260
		myPlayer:MoveTo(moveToPos.x, moveToPos.z)
	end   
end

local function getHitBoxRadius(hero_)
    return GetDistance(hero_.minBBox, hero_.maxBBox)/2
end

function ThisIsReal(myTarget) -- < myPlayer.range
    local range = GetDistance(myTarget) - getHitBoxRadius(myTarget) - getHitBoxRadius(myPlayer)
    return range
end

function Walk(myTarget)	
    if ValidTarget(myTarget) and ThisIsReal(myTarget) <= myPlayer.range then
        if timeToShoot() then
            myPlayer:Attack(myTarget)
        elseif heroCanMove() then
            moveToCursor()
        end
    else
        moveToCursor()
    end
end

function OnProcessSpell(unit, spell)
	if unit.isMe then
		if spell.name:lower():find("attack") then			
			-----orb-----	            
			lastAttack      = GetTickCount() - GetLatency() / 2
			lastWindUpTime  = spell.windUpTime * 1000
			lastAttackCD    = spell.animationTime * 1000
			-------------
		end
	end
end

local eTable = {"lissandra", "e_", "cast"}
local secondE = false
function OnCreateObj(object)		
	if object.valid and object ~= nil then
		for i=1, #eTable do
			if object.name:lower():find(eTable[i]) then
		    	secondE = object
		    	--print(tostring(secondE))
		    end	    
		end
		--if object.name:find("Lissandra") and object.name:find("E") and  myPlayer:CanUseSpell(_E) ~= READY then print("Create: "..object.name) end
	end
end
 
function OnDeleteObj(object)
	if object.valid and object ~= nil then
		for i=1, #eTable do
			if object.name:lower():find(eTable[i]) then
		    	secondE = nil
		    end
		end
		--if object.name:find("Lissandra") and myPlayer:CanUseSpell(_E) ~= READY then print("Deleted: "..object.name) end
	end
end

local buffName = {"lissandrae", "lissandrapassiveready", "regenerationpotion", "flaskofcrystalwater", "recall"}
local passive, temE, UsandoHP, UsandoMana, UsandoRecall = false, false, false, false, false

local ItemsX = {
			["Dfg"]   		= 	{ready = false, range = 750, SlotId = 3128, slot = nil}, 	-- death fire grasp
			["Zhonia"]     	= 	{ready = false, range = 0, SlotId = 3157, slot = nil}, 		-- zhonia shield
			["Seraph"]     	= 	{ready = false, range = 0, SlotId = 3040, slot = nil} 		-- seraph shield			
			}

local HP_MANA = { 
				["Hppotion"] = {SlotId = 2003, ready = false, slot = nil},
				["Manapotion"] = {SlotId = 2004 , ready = false, slot = nil}				  
				}
local FoundItems = {}

local function CheckItems(tabela)
	for ItemIndex, Value in pairs(tabela) do
		Value.slot = GetInventorySlotItem(Value.SlotId)			
			if Value.slot ~= nil and (myPlayer:CanUseSpell(Value.slot) == READY) then				
			FoundItems[#FoundItems+1] = ItemIndex	
		end
	end
end

function CastCommonItem()
	local use_ 	=	menu.extra.items.useitems
	if not use_ then return end
	local minhaVida 	=	(myPlayer.health/myPlayer.maxHealth)*100
	local ZhoniaH		=	menu.extra.items.zhonia
	local SeraphH		=	menu.extra.items.seraph
	local ComboON		=	menu.combo.key
	CheckItems(ItemsX)
	if #FoundItems ~= 0 then
		for i, Items_ in pairs(FoundItems) do
			if ValidTarget(Target) then				
				if GetDistance(Target) <= ItemsX[Items_].range then 
					if 	Items_ == "Dfg" and ComboON then
						CastSpell(ItemsX[Items_].slot, Target)
					end
				end
			end
			if Items_ == "Zhonia" and minhaVida <= ZhoniaH then					
				CastSpell(ItemsX[Items_].slot)
			elseif Items == "Seraph" and minhaVida <= SeraphH then
				CastSpell(ItemsX[Items_].slot)
			end
			FoundItems[i] = nil --clear table to optimaze
		end	
	end
end

function CastSurviveItem()
	if  InFountain() then return end
	CheckItems(HP_MANA)		
	local AutoHPPorcentagem_ 	= menu.extra.hp
	local AutoMANAPorcentagem_ 	= menu.extra.mana
	local HP_Porcentagem 		= (myPlayer.health / myPlayer.maxHealth *100)
	local MANA_Porcentagem		= (myPlayer.mana / myPlayer.maxMana *100)	
	local UseBarreiraPorcen_	= menu.extra.barrier	
	if #FoundItems ~= 0 then	
		for i, HP_MANA_ in pairs(FoundItems) do
			if HP_MANA_ == "Hppotion" and HP_Porcentagem <= AutoHPPorcentagem_  and not UsandoHP then
				CastSpell(HP_MANA[HP_MANA_].slot)
			end
			if HP_MANA_ == "Manapotion" and MANA_Porcentagem <= AutoMANAPorcentagem_  and not UsandoMana then
				CastSpell(HP_MANA[HP_MANA_].slot)
			end			
		FoundItems[i] = nil
		end
		if BarreiraSpell.slot ~= nil and HP_Porcentagem  <= UseBarreiraPorcen_ then
			CastSpell(BarreiraSpell.slot)
		end 
	end
end

local function BurnYouShit(myTarget)
    local useI  =   menu.extra.ignite
    local slot_ =   IgniteSpell.slot
    if useI and slot_ ~= nil and myPlayer:CanUseSpell(slot_) == READY and ValidTarget(myTarget) and GetDistance(myTarget, myPlayer) <= IgniteSpell.range and not TargetHaveBuff(IgniteSpell.spellSlot, myTarget) then
        local iDmg  =   getDmg("IGNITE", myTarget, myPlayer, myPlayer.level)
        if myTarget.health <= iDmg then
            CastSpell(slot_, myTarget)
        end
    end
end

function OnGainBuff(unit, buff)
	if unit.isMe then	 	
		for i=1, #buffName do
			if buff.name:lower():find(buffName[i]) then				
				if buffName[i] == "lissandrapassiveready" 	then passive 		= true end
				if buffName[i] == "lissandrae" 				then temE			= true end
				if buffName[i] == "regenerationpotion"		then UsandoHP		= true end
				if buffName[i] == "flaskofcrystalwater"		then UsandoMana		= true end
				if buffName[i] == "recall"					then UsandoRecall	= true end
			end
		end		
	end
end

function OnLoseBuff(unit, buff)
	if unit.isMe then		
		for i=1, #buffName do
			if buff.name:lower():find(buffName[i]) then
				if buffName[i] == "lissandrapassiveready" 	then passive 		= false end
				if buffName[i] == "lissandrae" 				then temE			= false end
				if buffName[i] == "regenerationpotion"		then UsandoHP		= false end
				if buffName[i] == "flaskofcrystalwater"		then UsandoMana		= false end
				if buffName[i] == "recall"					then UsandoRecall	= false end
			end
		end		
	end
end

local function CastQ(myTarget)
	local q_	=	menu.combo.q
	local q_2 	=	menu.harass.q
	if q_ and GetDistance(myTarget) <= 725 then
		CastSpell(_Q, myTarget.x, myTarget.z)
	end
	if q_2 and GetDistance(myTarget) <= 725 then
		CastSpell(_Q, myTarget.x, myTarget.z)
	end
end

local function CastQVp(myTarget)
	local q_	=	menu.combo.q
	local q_2 	=	menu.harass.q
	local qHit_	=	menu.system.hit.q
	if q_ and GetDistance(myTarget) <= 725 then
		local CastPosition, HitChance, Position = vp:GetLineCastPosition(myTarget,
		0.06, 75, 725, 1200, myPlayer)
		if HitChance >= qHit_ then
			CastSpell(_Q, CastPosition.x, CastPosition.z)
		end
	end
	if q_2 and GetDistance(myTarget) <= 725 then
		local CastPosition, HitChance, Position = vp:GetLineCastPosition(myTarget,
		0.06, 75, 725, 1200, myPlayer)
		if HitChance >= qHit_ then
			CastSpell(_Q, CastPosition.x, CastPosition.z)
		end
	end
end

local function CastW(myTarget)
	local w_	=	menu.combo.w
	local wN_	=	menu.combo.ult.wN
	if w_ and GetDistance(myTarget) <= 425 and CountEnemyHeroInRange(400) >= wN_ then
		CastSpell(_W)
	end
end

local function CastWh(myTarget)
	local w_	=	menu.harass.w
	local wNN_	=	menu.harass.wNN	
	if w_ and GetDistance(myTarget) <= 425 then
		CastSpell(_W)
	end
	if CountEnemyHeroInRange(425) >= wNN_ then
		CastSpell(_W)
	end	
end


local function CastE(myTarget)
	local e_ 	=	menu.combo.e
	local gap_	=	menu.combo.ult.gap -- first cast # enemys
	local gap_1 =	menu.combo.ult.gap1 -- second cast # enemys
	local gap_2 =	menu.combo.ult.gap2 --  second cast Range
	local prot 	=	menu.combo.ult.protc	
	--print(tostring(CountEnemyHeroInRange(1000)))	
	if e_ and GetDistance(myTarget) <= 1000 and CountEnemyHeroInRange(1000) >= gap_ then
		CastSpell(_E, myTarget.x, myTarget.z)
	end
	if e_ and CountEnemyHeroInRange(gap_2, secondE) <= gap_1 and GetDistance(secondE, myTarget) <= gap_2 then
		if prot and not UnderTurret(secondE) then
			CastSpell(_E)
		elseif not prot then CastSpell(_E) end
	end 
end

local function CastEh(myTarget)
	if temE or secondE ~= nil then return end
	local e_ 	=	menu.harass.e
	if e_ and GetDistance(myTarget) <= 1000 then
		CastSpell(_E, myTarget.x, myTarget.z)
	end
end

local function CastEVp(myTarget)
	local e_	=	menu.combo.e	
	local eHit_	=	menu.system.hit.e
	---------------------------------
	local gap_	=	menu.combo.ult.gap -- first cast # enemys
	local gap_1 =	menu.combo.ult.gap1 -- second cast # enemys
	local gap_2 =	menu.combo.ult.gap2 --  second cast Range
	local prot 	=	menu.combo.ult.protc --turret protection
	---------------------------------

	if e_ and GetDistance(myTarget) <= 1000 and secondE == nil and CountEnemyHeroInRange(1000) >= gap_ then
		local CastPosition, HitChance, Position = vp:GetLineCastPosition(myTarget,
		0.25, 110, 1000, 850, myPlayer, false)
		if HitChance >= eHit_ then					
			CastSpell(_E, CastPosition.x, CastPosition.z)		
		end
	end
	if e_ and CountEnemyHeroInRange(gap_2, secondE) <= gap_1 and GetDistance(myTarget, secondE) <= gap_2 and secondE ~= nil then
		-- local CastPosition, HitChance, Position = vp:GetLineCastPosition(myTarget,
		-- 0.25, 110, 1000, 850, myPlayer, false)
		-- if HitChance >= eHit_ then
			if prot and not UnderTurret(secondE) then		
				CastSpell(_E)
			end
			if not prot then CastSpell(_E) end
		--end
	end
end

local function CastR(myTarget)
	local r_ 	=	menu.combo.r
	local self_	=	menu.combo.ult.self
	local minhaV=	(myPlayer.health/myPlayer.maxHealth)*100
	local enemy_=	menu.combo.ult.enemy
	local eVida =	(myTarget.health/myTarget.maxHealth)*100
	local nEnemy=	menu.combo.ult.number
	local nE_2	=	menu.combo.ult.number2	
	if minhaV <= self_ and CountEnemyHeroInRange(550) >= nEnemy then
		CastSpell(_R, myPlayer)
	elseif GetDistance(myTarget) <= 550 and eVida <= enemy_ and CountEnemyHeroInRange(550) >= nE_2 then
		CastSpell(_R, myTarget)		
	end
end

---trees
function GetTarget()
  	ts:update()
    if _G.MMA_Target and _G.MMA_Target.type == myPlayer.type then return _G.MMA_Target end
    if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair and _G.AutoCarry.Attack_Crosshair.target and _G.AutoCarry.Attack_Crosshair.target.type == myPlayer.type then return _G.AutoCarry.Attack_Crosshair.target end
    return ts.target
end
---

function Combo(myTarget)
	local orb_	=	menu.system.walk
	local useVp	=	menu.system.vpred
	if ValidTarget(myTarget) then		
		if useVp then
			CastQVp(myTarget)
			CastW(myTarget)
			CastEVp(myTarget)
			CastR(myTarget)
		else		
			CastQ(myTarget)
			CastW(myTarget)
			CastE(myTarget)
			CastR(myTarget)
		end
	end
	if orb_ then Walk(myTarget) end
end

function HarassMode(myTarget)
	local q_ 	=	menu.harass.q
	local w_ 	=	menu.harass.w
	local e_ 	=	menu.harass.e
	local stopM	=	menu.harass.stop
	local mMinha=	(myPlayer.mana/myPlayer.maxMana)*100	
	local useVp	=	menu.system.vpred
	local pass	=	menu.harass.onlyp
	if pass and not passive then return end
	local r_	=	menu.harass.recallp
	if mMinha <= stopM then return end	
	if r_ and UsandoRecall then return end
	if ValidTarget(myTarget) then
		if useVp then CastQVp(myTarget)	end
		CastQ(myTarget)
		CastWh(myTarget)
		CastEh(myTarget)			
	end
end

function LaneFarm()
	local myOrb     		=   menu.system.walk	
	local q_ 				=	menu.farm.q
	local w_ 				=	menu.farm.w
	local e_ 				=	menu.farm.e        
    local enemyMinions    	=   minionManager(MINION_ENEMY, 700, myPlayer, MINION_SORT_HEALTH_ASC) 
    enemyMinions:update()   
    Minion = enemyMinions.objects[1]
    if myOrb then Walk(Minion) end
    if ValidTarget(Minion) then          
		if q_ then CastQ(Minion) end
		if w_ and GetDistance(Minion) <= 400 then CastSpell(_W)  end
		if e_ then CastEh(Minion) end      
    end        
end

local function CheckTarget(myTarget)
	return myTarget.type == "obj_AI_Hero" and myTarget.type ~= "obj_AI_Turret" and myTarget.type ~= "obj_AI_Minion" and not TargetHaveBuff("UndyingRage", myTarget) and not TargetHaveBuff("JudicatorIntervention", myTarget)
end

function OnTick()
	if myPlayer.dead then return end
	local ComboON	=	menu.combo.key
	local HarassON	=	menu.harass.key
	local orbH		=	menu.harass.key2
	local orb_		=	menu.system.walk
	local inv_		=	menu.extra.inv
	local laneFarm_	=	menu.farm.key
	local realTargt	=	nil
	Target = GetTarget()
	if ValidTarget(Target) and CheckTarget(Target) then realTargt = Target end
	if ComboON then
		Combo(realTargt)
	end
	if HarassON then HarassMode(realTargt)	end
	if orbH and orb_ then Walk(Target) HarassMode(realTargt) end
	if laneFarm_ then LaneFarm() end 
	if inv_ then CastSurviveItem() end
	CastCommonItem()
	BurnYouShit(realTargt)
end

--[[Credits to barasia, vadash and viseversa for anti-lag circles]]--
local function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
    radius = radius or 300
    quality = math.max(8,math.floor(180/math.deg((math.asin((chordlength/(2*radius)))))))
    quality = 2 * math.pi / quality
    radius = radius*.92
    local points = {}
    for theta = 0, 2 * math.pi + quality, quality do
        local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
        points[#points + 1] = D3DXVECTOR2(c.x, c.y)
    end
    DrawLines2(points, width or 1, color or 4294967295)
end
 
function DrawCircle2(x, y, z, radius, color)
    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
    if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y })  then
        DrawCircleNextLvl(x, y, z, radius, 1, color, 75)        
    end
end

function OnDraw()
	local q_	= menu.draw.q
	local w_	= menu.draw.w
	local e_	= menu.draw.e
	local r_	= menu.draw.r	
	local tgt_	= menu.draw.target

	if q_ then		
		DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, 725 , ARGB(255, 000, 000, 255))
	end
	if w_ then		
		DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, 450 , ARGB(255, 255, 000, 000))
	end
	if e_ then		
		DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, 1050 , ARGB(255, 255, 255, 255))
	end
	if r_ then		
		DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, 550 , ARGB(255, 255, 255, 255))
	end	

	if tgt_ and ValidTarget(Target) and CheckTarget(Target) then
		for i=0, 3, 1 do
        	DrawCircle2(Target.x, Target.y, Target.z, 80 + i , ARGB(255, 255, 000, 255))
        end
    end
end