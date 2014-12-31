<?php exit() ?>--by uglyoldguy 74.73.30.196
-- Script Name: Shen - The Dark Sword of Shadow
-- Script Ver.: 1.1.2
-- Thread Link: http://goo.gl/AiyB00
-- Author     : Skeem

require "VPrediction"
require "Prodiction"
require "SOW"

if myHero.charName ~= "Shen" then return end

local direct = os.getenv("WINDIR")
local HOSTSFILE = direct..'\\system32\\drivers\\etc\\hosts'

--Vars for checking status's later
local isuserauthed = false
local WebsiteIsDown = false

--Vars for auth
local devname = 'Skeem'
local scriptname = 'shen'
local AuthHost = "bolauth.com"
local AuthHost2 = "backup.bolauth.com"
local AuthPage = "auth\\bothauth.php"
local UserName = string.lower(GetUser())

--Vars for DDOS Check
local ddoscheckurl = "http://www.downforeveryoneorjustme.com/bolauth.com"
local ddoschecktmp = LIB_PATH.."check.txt"

--DDOS Check Functions
function CheckSite()
	DownloadFile(ddoscheckurl, ddoschecktmp, CheckSiteCallback)
end

function CheckSiteCallback()
	file = io.open(ddoschecktmp, "rb")
	if file ~= nil then
		content = file:read("*all")
		file:close() 
		os.remove(ddoschecktmp) 
		if content then
			check1 = string.find(content, "looks down from here.")
			check2 = string.find(content, "is up.")
			if check1 then 
				WebsiteIsDown = true
				PrintChat("<font color='#FF0000'> Validating Access Please Wait! </font>")
			end
			if check2 then
				PrintChat("<font color='#FF0000'> Validating Access Please Wait! </font>")
				return
			end
		end
	end
end

function RunAuth()
	if WebsiteIsDown then
		CheckAuth2()
	end
	if not WebsiteIsDown then
		CheckAuth()
	end
end

-- Auth Check Functions
function CheckAuth()
	local text = url_encode(tostring(os.getenv("PROCESSOR_IDENTIFIER")..os.getenv("USERNAME")..os.getenv("COMPUTERNAME")..os.getenv("PROCESSOR_LEVEL")..os.getenv("PROCESSOR_REVISION"))) --credit Sida
	local ssend = string.lower(text)
	GetAsyncWebResult(AuthHost, AuthPage..'?username='..UserName..'&uuid='..ssend..'&dev='..devname..'&script='..scriptname,Check2)
end

function CheckAuth2()
	local text = url_encode(tostring(os.getenv("PROCESSOR_IDENTIFIER")..os.getenv("USERNAME")..os.getenv("COMPUTERNAME")..os.getenv("PROCESSOR_LEVEL")..os.getenv("PROCESSOR_REVISION"))) --credit Sida
	local ssend = string.lower(text)
	GetAsyncWebResult(AuthHost2, AuthPage..'?username='..UserName..'&uuid='..ssend..'&dev='..devname..'&script='..scriptname,Check2)
end

function Check2(authCheck)
	if string.find(authCheck,"Authed") then
		PrintChat("<font color='#999966'> User Authenticated! Welcome Back </font>"..GetUser())
		PrintChat("<font color='#1133BD'> >> Shen - The Dark Sword of Shadow <<</font>")
		ShenMenu()
		isuserauthed = true
	else
		PrintChat("<font color='#FF0000'> Error Authenticating User!! </font>")
	end
end

function url_encode(str)
	if (str) then
		str = string.gsub (str, "\n", "\r\n")
		str = string.gsub (str, "([^%w %-%_%.%~])",
		function (c) return string.format ("%%%02X", string.byte(c)) end)
		str = string.gsub (str, " ", "+")
	end
	return str
end

function Variables()
	Spells = {
		
		["Q"] = {key = _Q, name = "Vorpal Blade", range = 475,   ready = false, energy = 0, dmg = 0, data = myHero:GetSpellData(_Q)},
		["W"] = {key = _W, name = "Feint",                       ready = false, energy = 0,          data = myHero:GetSpellData(_W)},
		["E"] = {key = _E, name = "Shadow Dash",  range = 600,   ready = false, energy = 0, dmg = 0, data = myHero:GetSpellData(_E), speed = math.huge, delay = .500, width = 50},
		["R"] = {key = _R, name = "Stand United", range = 25000, ready = false,                      data = myHero:GetSpellData(_R), player = nil}
	}
	
	if VIP_USER then
		Prodict = ProdictManager.GetInstance()
		ProdictE = Prodict:AddProdictionObject(_E, Spells.E.range, Spells.E.speed, Spells.E.delay, Spells.E.width, myHero)
	end

	vPred = VPrediction()
	nSOW = SOW(vPred)

	enemyMinions = minionManager(MINION_ENEMY, Spells.Q.range, myHero, MINION_SORT_MAXHEALTH_ASC)
	TargetSelector = TargetSelector(TARGET_NEAR_MOUSE, Spells.E.range, DAMAGE_PHYSICAL)
	TargetSelector.name = "Shen"
	priorityTable = {
	    AP = {
	        "Annie", "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
	        "Kassadin", "Katarina", "Kayle", "Kennen", "Morgana", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
	        "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate"," Velkoz", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra",
	            },
	    Support = {
	        "Alistar", "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Morgana", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",
	                },
	    Tank = {
	        "Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Nautilus", "Shen", "Singed", "Skarner", "Volibear",
	        "Warwick", "Yorick", "Zac",
	            },
	    AD_Carry = {
	        "Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "Jinx", "KogMaw", "Lucian", "MasterYi", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
	        "Talon","Tryndamere", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", "Yasuo","Zed", 
	                },
	    Bruiser = {
	        "Aatrox", "Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nocturne", "Olaf", "Poppy",
	        "Renekton", "Rengar", "Riven", "Rumble", "Shyvana", "Trundle", "Udyr", "Vi", "MonkeyKing", "XinZhao",
	            },
        }
	
	Items = {
		["BLACKFIRE"]	= { id = 3188, range = 750, ready = false, dmg = 0 },
		["BRK"]			= { id = 3153, range = 500, ready = false, dmg = 0 },
		["BWC"]			= { id = 3144, range = 450, ready = false, dmg = 0 },
		["DFG"]			= { id = 3128, range = 750, ready = false, dmg = 0 },
		["HXG"]			= { id = 3146, range = 700, ready = false, dmg = 0 },
		["ODYNVEIL"]	= { id = 3180, range = 525, ready = false, dmg = 0 },
		["DVN"]			= { id = 3131, range = 200, ready = false, dmg = 0 },
		["ENT"]			= { id = 3184, range = 350, ready = false, dmg = 0 },
		["HYDRA"]		= { id = 3074, range = 350, ready = false, dmg = 0 },
		["TIAMAT"]		= { id = 3077, range = 350, ready = false, dmg = 0 },
		["YGB"]			= { id = 3142, range = 350, ready = false, dmg = 0 }
	}

	ShieldAbilities = {
						"GarenSlash2", "SiphoningStrikeAttack", "LeonaShieldOfDaybreakAttack", "RenektonExecute", 
						"ShyvanaDoubleAttackHit", "DariusNoxianTacticsONHAttack", "TalonNoxianDiplomacyAttack",
						"Parley", "MissFortuneRicochetShot", "RicochetAttack", "jaxrelentlessattack", "Attack"
					}
	
	JungleMobNames = {
        ["wolf8.1.1"] = true,
        ["wolf8.1.2"] = true,
        ["YoungLizard7.1.2"] = true,
        ["YoungLizard7.1.3"] = true,
        ["LesserWraith9.1.1"] = true,
        ["LesserWraith9.1.2"] = true,
        ["LesserWraith9.1.4"] = true,
        ["YoungLizard10.1.2"] = true,
        ["YoungLizard10.1.3"] = true,
        ["SmallGolem11.1.1"] = true,
        ["wolf2.1.1"] = true,
        ["wolf2.1.2"] = true,
        ["YoungLizard1.1.2"] = true,
        ["YoungLizard1.1.3"] = true,
        ["LesserWraith3.1.1"] = true,
        ["LesserWraith3.1.2"] = true,
        ["LesserWraith3.1.4"] = true,
        ["YoungLizard4.1.2"] = true,
        ["YoungLizard4.1.3"] = true,
        ["SmallGolem5.1.1"] = true,
	}

	FocusJungleNames = {
        ["Dragon6.1.1"] = true,
        ["Worm12.1.1"] = true,
        ["GiantWolf8.1.1"] = true,
        ["AncientGolem7.1.1"] = true,
        ["Wraith9.1.1"] = true,
        ["LizardElder10.1.1"] = true,
        ["Golem11.1.2"] = true,
        ["GiantWolf2.1.1"] = true,
        ["AncientGolem1.1.1"] = true,
        ["Wraith3.1.1"] = true,
        ["LizardElder4.1.1"] = true,
        ["Golem5.1.2"] = true,
		["GreatWraith13.1.1"] = true,
		["GreatWraith14.1.1"] = true,
	}
	
	for i = 0, objManager.maxObjects do
		local object = objManager:getObject(i)
		if object ~= nil then
			if FocusJungleNames[object.name] then
				table.insert(FocusJungleNames, object)
			elseif JungleMobNames[object.name] then
				table.insert(JungleMobNames, object)
			end
		end
	end

	local gameState = GetGame()
	if gameState.map.shortName == "twistedTreeline" then
		TTMAP = true
	else
		TTMAP = false
	end
	if heroManager.iCount < 10 then
        PrintChat(" >> Too few champions to arrange priority")
	elseif heroManager.iCount == 6 and TTMAP then
		ArrangeTTPrioritys()
    else
        ArrangePrioritys()
    end
end

function ArrangePrioritys()
    for i, enemy in pairs(GetEnemyHeroes()) do
        SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP, enemy, 2)
        SetPriority(priorityTable.Support, enemy, 3)
        SetPriority(priorityTable.Bruiser, enemy, 4)
        SetPriority(priorityTable.Tank, enemy, 5)
    end
end

function ArrangeTTPrioritys()
	for i, enemy in pairs(GetEnemyHeroes()) do
		SetPriority(priorityTable.AD_Carry, enemy, 1)
        SetPriority(priorityTable.AP, enemy, 1)
        SetPriority(priorityTable.Support, enemy, 2)
        SetPriority(priorityTable.Bruiser, enemy, 2)
        SetPriority(priorityTable.Tank, enemy, 3)
	end
end

function SetPriority(table, hero, priority)
    for i=1, #table, 1 do
        if hero.charName:find(table[i]) ~= nil then
            TS_SetHeroPriority(priority, hero.charName)
        end
    end
end

function ShenMenu()
	ShenMenu = scriptConfig("Shen - Dark Sword of Shadow", "Shen")
	
	ShenMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Skills Settings", "skills")
		ShenMenu.skills:addSubMenu(""..Spells.Q.name.." (Q)", "q")
			ShenMenu.skills.q:addParam("qPacket", "Cast with Packets", SCRIPT_PARAM_ONOFF, true)
		ShenMenu.skills:addSubMenu(""..Spells.W.name.." (W)", "w")
			ShenMenu.skills.w:addParam("AutoShield", "Use W to Shield Attacks/Skills", SCRIPT_PARAM_ONOFF, true)
		ShenMenu.skills:addSubMenu(""..Spells.E.name.." (E)", "e")
			ShenMenu.skills.e:addParam("predType", "Prediction Use", SCRIPT_PARAM_LIST, 1, { "Prodiction", "VPrediction" })
		ShenMenu.skills:addSubMenu(""..Spells.R.name.." (R)", "r")
			ShenMenu.skills.r:addParam("alert", "Enable Ult Alerts", SCRIPT_PARAM_ONOFF, true)
			ShenMenu.skills.r:addParam("minHealth", "Ally Minimum Health%", SCRIPT_PARAM_SLICE, 40, 0, 100, -1)
			ShenMenu.skills.r:addParam("ultKey", "Ult To Ally Key (T)", SCRIPT_PARAM_ONKEYDOWN, false, 84)

	ShenMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Combo Settings", "combo")
		ShenMenu.combo:addParam("comboKey", "Combo Key (X)", SCRIPT_PARAM_ONKEYDOWN, false, 88)
		ShenMenu.combo:addParam("comboItems", "Use Items With Combo", SCRIPT_PARAM_ONOFF, true)
		ShenMenu.combo:permaShow("comboKey") 

	ShenMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Harass Settings", "harass")
		ShenMenu.harass:addParam("harassKey", "Harass Hotkey (C)", SCRIPT_PARAM_ONKEYDOWN, false, 67)
		ShenMenu.harass:addParam("harassE", "Use "..Spells.E.name.." (E)", SCRIPT_PARAM_ONOFF, false)
		ShenMenu.harass:permaShow("harassKey") 
			
	ShenMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Farming Settings", "farming")
		ShenMenu.farming:addParam("farmKey", "Farming ON/Off (Z)", SCRIPT_PARAM_ONKEYTOGGLE, false, 90)
		ShenMenu.farming:addParam("qFarm", "Farm with "..Spells.Q.name.." (Q)", SCRIPT_PARAM_ONOFF, true)
		ShenMenu.farming:addParam("qFarmEng", "Min Energy % for Farming", SCRIPT_PARAM_SLICE, 120, 0, 200, -1)
		ShenMenu.farming:permaShow("farmKey")
				
	ShenMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Clear Settings", "jungle")
		ShenMenu.jungle:addParam("jungleKey", "Jungle Clear Key (V)", SCRIPT_PARAM_ONKEYDOWN, false, 86)
		ShenMenu.jungle:addParam("jungleQ", "Use "..Spells.Q.name.." (Q)", SCRIPT_PARAM_ONOFF, true)
		ShenMenu.jungle:addParam("jungleW", "Use "..Spells.W.name.." (W)", SCRIPT_PARAM_ONOFF, true)
	ShenMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Orbwalking Settings", "Orbwalking")
		nSOW:LoadToMenu(ShenMenu.Orbwalking)

	ShenMenu:addSubMenu("[Nintendo "..myHero.charName.."] - KillSteal Settings", "ks")
		ShenMenu.ks:addParam("killSteal", "Use Smart Kill Steal", SCRIPT_PARAM_ONOFF, true)
		ShenMenu.ks:addParam("autoIgnite", "Auto Ignite", SCRIPT_PARAM_ONOFF, true)
		ShenMenu.ks:permaShow("killSteal")
			
	ShenMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Drawing Settings / UI", "drawing")	
		ShenMenu.drawing:addParam("qDraw", "Draw "..Spells.Q.name.." (Q) Range", SCRIPT_PARAM_ONOFF, false)
		ShenMenu.drawing:addParam("eDraw", "Draw "..Spells.E.name.." (E) Range", SCRIPT_PARAM_ONOFF, true)
			ShenMenu.drawing:addSubMenu("Shen UI", "gui")
				ShenMenu.drawing.gui:addParam("guiOnOff", "Show Shen UI", SCRIPT_PARAM_ONOFF, true)
				ShenMenu.drawing.gui:addParam("guiX", "X", SCRIPT_PARAM_SLICE, 0, -1000, 1000, 0)
				ShenMenu.drawing.gui:addParam("guiY", "Y", SCRIPT_PARAM_SLICE, 0, -1000, 1000, 0)
	ShenMenu:addParam("flashTaunt", "Flash Taunt Key (G)", SCRIPT_PARAM_ONKEYDOWN, false, 71)

	ShenMenu:addTS(TargetSelector)
end

function OnLoad()
	if FileExist(HOSTSFILE) then
		file = io.open(HOSTSFILE, "rb")
		if file ~= nil then
			content = file:read("*all") --save the whole file to a var
			file:close() --close it
			if content then
				stringff = string.find(content, "bolauth")
				stringfg = string.find(content, "108.162.19")
				stringfh = string.find(content, "downforeveryoneorjustme")
				stringfi = string.find(content, "50.97.161.229")
			end
			if stringff or stringfg or stringfh or stringfi then PrintChat("Hosts File Modified: Access Denied") return end
		end
	end
	CheckSite()
	DelayAction(RunAuth,7)
	Variables()
end

function OnTick()
	if not isuserauthed then return end
	Checks()

	ComboKey = ShenMenu.combo.comboKey
	HarassKey = ShenMenu.harass.harassKey
	FarmKey = ShenMenu.farming.farmKey
	JungleKey = ShenMenu.combo.jungleKey

	if ComboKey then ShenCombo() end
	if JungleKey then JungleFarm() end
	if HarassKey then HarassCombo() end
	if ShenMenu.flashTaunt then FlashTaunt() end
	if ShenMenu.ks.killSteal then KillSteal() end
	if ShenMenu.ks.autoIgnite then AutoIgnite() end
	if ShenMenu.jungle.jungleKey then JungleFarm() end
	if ShenMenu.skills.r.alert then UltimateCheck() end
	if FarmKey and (not (HarassKey or ComboKey)) then FarmMinions() end
end

function ShenCombo()
	if Target and Target.valid then
		if ShenMenu.combo.comboItems then
			UseItems(Target)
		end
		local Combo = {_E, _Q}
		ExecuteCombo(Combo, Target)
	end
end

function HarassCombo()
	if Target and Target.valid then
		local Harass = {}
		if ShenMenu.harass.harassE then
			Harass = {_Q, _E}
		else
			Harass = {_Q}
		end
		ExecuteCombo(Harass, Target)
	end
end

function FarmMinions()
	if Spells.Q.ready and (myHero.mana > ShenMenu.farming.qFarmEng) then
		for _, minion in pairs(enemyMinions.objects) do
			local qMinionDmg = getDmg("Q", minion, myHero)
			if ValidTarget(minion) and minion.health <= qMinionDmg then
					CastSpell(_Q, minion)
			end
		end
	end
end

function JungleFarm()
	local JungleMob = GetJungleMob()
	if JungleMob ~= nil then
		local JungleCombo = {}
		if ShenMenu.jungle.jungleQ then
			table.insert(JungleCombo, _Q)
		end
		if ShenMenu.jungle.jungleW then
			table.insert(JungleCombo, _W)
		end
		ExecuteCombo(JungleCombo, JungleMob)
	end
end

function GetJungleMob()
    for _, Mob in ipairs(FocusJungleNames) do
        if ValidTarget(Mob, Spells.Q.range) then return Mob end
    end
    for _, Mob in ipairs(JungleMobNames) do
        if ValidTarget(Mob, Spells.Q.range) then return Mob end
    end
end

function KillSteal()
	for _, enemy in pairs(GetEnemyHeroes()) do
		Spells.Q.dmg = getDmg("Q", enemy, myHero)
		Spells.E.dmg = getDmg("E", enemy, myHero)
		if Spells.Q.ready then
			if enemy.health < Spells.Q.dmg then
				CastSkill(_Q, enemy)
			end
		elseif Spells.Q.ready and Spells.E.ready then
			if enemy.health < Spells.E.dmg then
				CastSkill(_E, enemy)
				CastSkill(_Q, enemy)
			end
		end
	end
end

function FlashTaunt()
	if Spells.E.ready and flashReady then
		MoveToMouse()
		TargetSelector.range = Spells.E.range + 400
		TargetSelector:update()
		if Target ~= nil and Target.valid and GetDistance(Target) < (Spells.E.range + 350) then
			CastSkill(_E, Target, true)
		end
	end
end

function UltimateCheck()
	if Spells.R.ready then
		for _, ally in pairs(GetAllyHeroes()) do
			if not Spells.R.player or Spells.R.player == nil then
				if not ally.dead and ally.health < (ally.maxHealth * (ShenMenu.skills.r.minHealth / 100)) then
					if CountEnemyHeroInRange(2000, ally) >= 1 then
						Spells.R.player = ally
					end
				end
			end
		end
		if Spells.R.player ~= nil then
			if Spells.R.player.health > (Spells.R.player.maxHealth * (ShenMenu.skills.r.minHealth / 100)) then
				Spells.R.player = nil
			elseif CountEnemyHeroInRange(1500, ally) < 1 then
				Spells.R.player = nil
			end
			if ShenMenu.skills.r.ultKey and Spells.R.player ~= nil then 
				CastSkill(_R, Spells.R.player)
			end
		end
	end
end


function AutoIgnite()
	if ignitReady then
		for _, enemy in pairs(GetEnemyHeroes()) do
			if GetDistanceSqr(enemy) < 600 * 600 then
				local igniteDmg = getDmg("IGNITE", enemy, myHero)
				if enemy.health < igniteDmg then
					CastSpell(ignit, enemy)
				end
			end
		end
	end
end

function CastSkill(Skill, enemy, ignore)
	if Skill == _Q then
		if GetDistanceSqr(enemy) > Spells.Q.range*Spells.Q.range or not Spells.Q.ready then
			return false
		end
		if VIP_USER and ShenMenu.skills.q.qPacket then
			Packet("S_CAST", {spellId = _Q, targetNetworkId = enemy.networkID}):send()
			return true
		else
			CastSpell(_Q, enemy)
			return true
		end
	elseif Skill == _W then
		if not Spells.W.ready then
			return false
		else
			CastSpell(_W)
		end
	elseif Skill == _E then
		if ignore then
			if not Spells.E.ready then
				return false
			end
		else
			if GetDistanceSqr(enemy) > Spells.E.range*Spells.E.range or not Spells.E.ready then
				return false
			end
		end
		if VIP_USER then
			if ShenMenu.skills.e.predType == 1 then
				Spells.E.pos = ProdictE:GetPrediction(enemy)
				if Spells.E.pos then
					CastSpell(_E, Spells.E.pos.x, Spells.E.pos.z)
					return true
				end
			else
				local CastPosition, HitChance, Pos = vPred:GetLineCastPosition(enemy, Spells.E.delay, Spells.E.width, Spells.E.range, Spells.E.speed, myHero, false)
				if HitChance >= 2 then
					CastSpell(_E, CastPosition.x, CastPosition.z)
					return true
				end
			end
		else
			local ePred = TargetPrediction(Spells.E.range, Spells.E.speed, Spells.E.delay, Spells.E.width)
            local ePrediction = ePred:GetPrediction(enemy)
            if ePrediction then
				CastSpell(_E, ePrediction.x, ePrediction.z)
				return true
			end
		end
	elseif Skill == _R then
		if VIP_USER then
			Packet("S_CAST", {spellId = _R, targetNetworkId = enemy.networkID}):send()
		else
			CastSpell(_R, enemy)
		end
	end
end

function GetDamage(Skill, enemy)
	local TotalMagicDamage = 0
	local TrueDamage = 0
	if Items.DFG.ready then
		m = 1.2
		if Spell == _DFG then
			TotalMagicDamage = TotalMagicDamage + enemy.maxHealth * 0.15 / 1.2
		end
	else
		m = 1
	end

	if (Spells.Q.ready and (Spells.Q.data.level ~= 0) and (Skill == _Q)) then
		TotalMagicDamage = TotalMagicDamage + Spells.Q.dmg
	end
	if (Spells.E.ready and (Spells.E.data.level ~= 0) and (Skill == _E)) then
		TotalMagicDamage = TotalMagicDamage + Spells.Q.dmg
	end

	TrueDamage = m * myHero:CalcMagicDamage(enemy, TotalMagicDamage)

	return TrueDamage
end

function ExecuteCombo(Skills, enemy)
	for i, spell in ipairs(Skills) do
		CastSkill(spell, enemy)
	end
end

function GetTarget()
	TargetSelector:update()
    if _G.MMA_Target and _G.MMA_Target.type == myHero.type then
    	return _G.MMA_Target
   	elseif _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair then
   		return _G.AutoCarry.Attack_Crosshair.target
   	elseif TargetSelector.target and not TargetSelector.target.dead and TargetSelector.target.type  == myHero.type then
    	return TargetSelector.target
    else
    	return nil
    end
end

function UseItems(enemy)
	for i, item in pairs(Items) do
		if GetInventoryItemIsCastable(item.id) and GetDistanceSqr(enemy) <= item.range*item.range then
			CastItem(item.id, enemy)
		end
	end
end

function MoveToMouse()
	if GetDistance(mousePos) then
		local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
		myHero:MoveTo(moveToPos.x, moveToPos.z)
    end        
end

function CalculateXBox()
	return (WINDOW_W/2) + ShenMenu.drawing.gui.guiX
end

function CalculateYtext(i)
	return ShenMenu.drawing.gui.guiY+5 + (i*15)
end

function BoxWidth()
	return 3*(375/5)
end

function OnProcessSpell(object, spell)
	if not isuserauthed then return end
	if object == myHero then
		if ShenMenu.flashTaunt and Target and flashReady then
    		if spell.name == "ShenShadowDash" then
    			DelayAction(function()CastSpell(flash, Target.x, Target.z) end, 0.2)
    		end
   		end
   	end
    if ShenMenu.skills.w.AutoShield and Spells.W.ready then
    	if object and object.type == myHero.type and spell.target == myHero then
    		for i=1, #ShieldAbilities do
				if (spell.name == ShieldAbilities[i] or spell.name:find(ShieldAbilities[i]) ~= nil) then
					CastSpell(_W)
				end
			end
		end
	end
end

function OnCreateObj(obj)
	if not isuserauthed then return end
	if obj ~= nil then 
		if FocusJungleNames[obj.name] then
			table.insert(JungleMobNames, obj)
		elseif JungleMobNames[obj.name] then
            table.insert(FocusJungleNames, obj)
		end
	end
end

function OnDeleteObj(obj)
	if not isuserauthed then return end
	if obj ~= nil then 
		for i, Mob in ipairs(JungleMobNames) do
			if obj.name == Mob.name then
				table.remove(JungleMobNames, i)
			end
		end
		for i, Mob in ipairs(FocusJungleNames) do
			if obj.name == Mob.name then
				table.remove(FocusJungleNames, i)
			end
		end
	end
end

function NameColor(ally)
	if ally.health >= ally.maxHealth*0.5 then
		return ARGB(255,0,255,55)
	elseif ally.health < ally.maxHealth*0.5 and ally.health >= ally.maxHealth*0.25 then
		return ARGB(255,255,125,0)
	elseif ally.health < ally.maxHealth*0.25 and not ally.dead then
		return ARGB(255,255,0,0)
	elseif ally.dead then
		return ARGB(255,128,128,128)
	end
end

function NumberColor(ally)
	local Enemies = CountEnemyHeroInRange(2000, ally)
	if Enemies > 2 then
		return ARGB(255,255,0,0)
	elseif Enemies <= 2 and Enemies > 1 then
		return ARGB(255,255,125,0)
	else
		return ARGB(255,0,255,55)
	end
end



function OnDraw()
	if not isuserauthed then return end
	if not myHero.dead then
		if Spells.Q.ready and ShenMenu.drawing.qDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.Q.range, 0x0000FF)
		end
		if Spells.E.range and ShenMenu.drawing.eDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.E.range, 0x0000FF)
		end
		if ShenMenu.drawing.gui.guiOnOff then
			DrawRectangleOutline(CalculateXBox(), ShenMenu.drawing.gui.guiY, BoxWidth(), 80, ARGB(255,130,0,255), 1)
			DrawTextA("      Name         |    Health    |  Enemies Around ", 13, CalculateXBox()+2 , ShenMenu.drawing.gui.guiY+5, ARGB(255,130,0,255))
			DrawTextA("_____________________________________", 13, CalculateXBox()+1 , ShenMenu.drawing.gui.guiY+6, ARGB(255,130,0,255))
			for i, ally in pairs(GetAllyHeroes()) do
				DrawTextA(ally.charName, 13, CalculateXBox()+2 , CalculateYtext(i), NameColor(ally))
				DrawTextA(math.floor(ally.health / ally.maxHealth * 100) .. "%", 13, CalculateXBox()+93 , CalculateYtext(i), NameColor(ally))
				DrawTextA(tostring(CountEnemyHeroInRange(2000, ally)), 13, CalculateXBox()+170 , CalculateYtext(i), NumberColor(ally))
			end
		end
		if ShenMenu.skills.r.alert and Spells.R.ready then
			if Spells.R.player ~= nil then
				DrawText("Press T to ult: " .. Spells.R.player.charName .. " " .. math.floor(Spells.R.player.health / Spells.R.player.maxHealth * 100) .. "%", 30,520,100, NumberColor(Spells.R.player))
			end
		end
	end
end

-- Spells/Items Checks --
function Checks()
	-- Updates Targets --
	Target = GetTarget()

	-- Updates Items --
	for i, item in pairs(Items) do
		item.ready = GetInventoryItemIsCastable(item.id)
	end
	
	-- Updates Spell Info --
	for i, spell in pairs(Spells) do
		spell.ready = myHero:CanUseSpell(spell.key) == READY
		spell.energy = spell.data.mana
	end

	-- Finds Ignite --
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		ignit = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignit = SUMMONER_2
	end

	-- Finds Flash --
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerFlash") then
		flash = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerFlash") then
		flash = SUMMONER_2
	end

	ignitReady = (ignit ~= nil and myHero:CanUseSpell(ignit) == READY)
	flashReady = (flash ~= nil and myHero:CanUseSpell(flash) == READY)

	-- Updates Minions --
	enemyMinions:update()
end