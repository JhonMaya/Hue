<?php exit() ?>--by UglyOldGuy 107.14.54.0
local REQUIRED_LIBS = {
	["VPrediction"] = "https://raw.githubusercontent.com/honda7/BoL/master/Common/VPrediction.lua",
	["SOW"]         = "https://raw.githubusercontent.com/honda7/BoL/master/Common/SOW.lua",
	["Prodiction"]	= "https://bitbucket.org/Klokje/public-klokjes-bol-scripts/raw/154ae5a9505b2af87c1a6049baa529b934a498a9/Common/Prodiction.lua"
}

local DOWNLOADING_LIBS, DOWNLOAD_COUNT = false, 0

function AfterDownload()
	DOWNLOAD_COUNT = DOWNLOAD_COUNT - 1
	if DOWNLOAD_COUNT == 0 then
		DOWNLOADING_LIBS = false
		print("<font color=\"#FF0000\"><b> Renekton - The Real Kaptain Skurvy:</b></font> <font color=\"#FFFFFF\">Required libraries downloaded successfully, please reload (double F9).</font>")
	end
end

for DOWNLOAD_LIB_NAME, DOWNLOAD_LIB_URL in pairs(REQUIRED_LIBS) do
	if FileExist(LIB_PATH .. DOWNLOAD_LIB_NAME .. ".lua") then
		require(DOWNLOAD_LIB_NAME)
	else
		DOWNLOADING_LIBS = true
		DOWNLOAD_COUNT = DOWNLOAD_COUNT + 1
		DownloadFile(DOWNLOAD_LIB_URL, LIB_PATH .. DOWNLOAD_LIB_NAME..".lua", AfterDownload)
	end
end

if DOWNLOADING_LIBS then return end

local direct = os.getenv("WINDIR")
local HOSTSFILE = direct..'\\system32\\drivers\\etc\\hosts'

--Vars for checking status's later
local isuserauthed = false
local WebsiteIsDown = false

--Vars for auth
local devname = 'Skeem'
local scriptname = 'renekton'
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
		RenektonMenu()
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
		
		["Q"] = {key = _Q, name = "Cull the Meek",      range = 320, ready = false, dmg = 0, data = myHero:GetSpellData(_Q)},
		["W"] = {key = _W, name = "Ruthless Predator",  range = 200, ready = false, dmg = 0, data = myHero:GetSpellData(_W)},
		["E"] = {key = _E, name = "Slice",              range = 450, ready = false, dmg = 0, data = myHero:GetSpellData(_E), speed = 200, delay = .5, width = 50, lastE = 0, dice = false},
		["R"] = {key = _R, name = "Dominus",            ready = false, dmg = 0, data = myHero:GetSpellData(_R)}
	}

	if VIP_USER then
		Prodict = ProdictManager.GetInstance()
		ProdictE = Prodict:AddProdictionObject(_E, Spells.E.range, Spells.E.speed, Spells.E.delay, Spells.E.width, myHero)
	end
	
	vPred = VPrediction()
	nSOW = SOW(vPred)

	TargetSelector = TargetSelector(TARGET_NEAR_MOUSE, Spells.E.range, DAMAGE_PHYSICAL)
	TargetSelector.name = "Renekton"

	priorityTable = {
	    AP = {
	        "Annie", "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
	        "Kassadin", "Katarina", "Kayle", "Kennen", "Renekton", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
	        "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra",
	            },
	    Support = {
	        "Alistar", "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",
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

function RenektonMenu()
	RenektonMenu = scriptConfig("Renekton - The Real Kaptain Skurvy", "Renekton")
	
	RenektonMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Skills Settings", "skills")
		RenektonMenu.skills:addSubMenu(""..Spells.E.name.." (E)", "e")
			RenektonMenu.skills.e:addParam("predType", "Prediction Use", SCRIPT_PARAM_LIST, 1, { "Prodiction", "VPrediction" })
		RenektonMenu.skills:addSubMenu(""..Spells.R.name.." (R)", "r")
			RenektonMenu.skills.r:addParam("autoUlt", "Auto Use "..Spells.R.name.." (R)", SCRIPT_PARAM_ONOFF, true)
			RenektonMenu.skills.r:addParam("towerUlt", "Auto Ult Tower Dives", SCRIPT_PARAM_ONOFF, true)
			RenektonMenu.skills.r:addParam("ultEnemies", "Auto Ult if # Enemies Around", SCRIPT_PARAM_SLICE, 2, 1, 5, 0)
			RenektonMenu.skills.r:addParam("ultHealth", "if health < %", SCRIPT_PARAM_SLICE, 40, 0, 100, -1)

	RenektonMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Combo Settings", "combo")
		RenektonMenu.combo:addParam("comboKey", "Combo Key (X)", SCRIPT_PARAM_ONKEYDOWN, false, 88)
		RenektonMenu.combo:addParam("comboE", "Use "..Spells.E.name.." (E)", SCRIPT_PARAM_ONOFF, true)
		RenektonMenu.combo:addParam("waitE", "Wait Before 2nd E", SCRIPT_PARAM_ONOFF, true)
		RenektonMenu.combo:addParam("comboItems", "Use Items With Combo", SCRIPT_PARAM_ONOFF, true)
		RenektonMenu.combo:permaShow("comboKey") 

	RenektonMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Harass Settings", "harass")
		RenektonMenu.harass:addParam("harassKey", "Harass Hotkey (C)", SCRIPT_PARAM_ONKEYDOWN, false, 67)
		RenektonMenu.harass:addParam("autoQ", "Auto "..Spells.Q.name.." (Q)", SCRIPT_PARAM_ONOFF, true)
		RenektonMenu.harass:addParam("harassE", "Use "..Spells.E.name.." (E)", SCRIPT_PARAM_ONOFF, false)
		RenektonMenu.harass:addParam("harassDice", "Use 2nd E to Go Back", SCRIPT_PARAM_ONOFF, true)
		RenektonMenu.harass:permaShow("harassKey") 
			
	RenektonMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Clear Settings", "jungle")
		RenektonMenu.jungle:addParam("jungleKey", "Jungle Clear Key (V)", SCRIPT_PARAM_ONKEYDOWN, false, 86)
		RenektonMenu.jungle:addParam("jungleQ", "Use "..Spells.Q.name.." (Q)", SCRIPT_PARAM_ONOFF, true)
		RenektonMenu.jungle:addParam("jungleW", "Use "..Spells.W.name.." (W)", SCRIPT_PARAM_ONOFF, true)
		RenektonMenu.jungle:addParam("jungleE", "Use "..Spells.E.name.." (E)", SCRIPT_PARAM_ONOFF, true)

	RenektonMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Orbwalking Settings", "Orbwalking")
		nSOW:LoadToMenu(RenektonMenu.Orbwalking)

	RenektonMenu:addSubMenu("[Nintendo "..myHero.charName.."] - KillSteal Settings", "ks")
		RenektonMenu.ks:addParam("killSteal", "Use Smart Kill Steal", SCRIPT_PARAM_ONOFF, true)
		RenektonMenu.ks:addParam("autoIgnite", "Auto Ignite", SCRIPT_PARAM_ONOFF, true)
		RenektonMenu.ks:permaShow("killSteal")
			
	RenektonMenu:addSubMenu("[Nintendo "..myHero.charName.."] - Drawing Settings", "drawing")	
		RenektonMenu.drawing:addParam("qDraw", "Draw "..Spells.E.name.." (E) Range", SCRIPT_PARAM_ONOFF, true)

	RenektonMenu:addTS(TargetSelector)
end

function OnLoad()
	CheckSite()
	DelayAction(RunAuth,7)
	Variables()
	PrintChat("<font color='#33CC33'> >> Renekton - The Real Kaptain Skurvy<<</font>")
end

function OnTick()
	if not isuserauthed then return end
	Checks()

	ComboKey = RenektonMenu.combo.comboKey
	JungleKey = RenektonMenu.combo.jungleKey
	HarassKey = RenektonMenu.harass.harassKey	

	if ComboKey then RenektonCombo() end
	if JungleKey then JungleFarm() end
	if HarassKey then HarassCombo() end
	if RenektonMenu.ks.killSteal then KillSteal() end
	if RenektonMenu.ks.autoIgnite then AutoIgnite() end
	if RenektonMenu.skills.r.autoUlt then AutoUlt() end
	if RenektonMenu.harass.autoQ and Target and Target.valid then
		CastSkill(_Q, Target)
	end
end

function RenektonCombo()
	if Target and Target.valid then
		if RenektonMenu.combo.comboItems then
			UseItems(Target)
		end
		CastSkill(_W, Target)
		CastSkill(_Q, Target)
		if RenektonMenu.combo.comboE then
			if not Spells.E.dice then
				CastSkill(_E, Target)
			else
				if RenektonMenu.combo.waitE and ((os.clock() - Spells.E.lastE) > 3.5) and Spells.E.dice then
					CastSkill(_E, Target)
				elseif not RenektonMenu.combo.waitE then
					CastSkill(_E, Target)
				end
			end
		end
	end
end

function HarassCombo()
	if Target and Target.valid then
		local SavePos = {x = myHero.x, y = myHero.z}
		if RenektonMenu.harass.harassE then
			if Spells.E.ready and not Spells.E.dice then
				CastSkill(_W, Target)
				CastSkill(_E, Target)
				CastSkill(_Q, Target)
			else
				CastSkill(_W, Target)
				CastSkill(_Q, Target)
			end
		else
			CastSkill(_W, Target)
			CastSkill(_Q, Target)
		end
		if RenektonMenu.harass.harassDice and Spells.E.dice then
			if not Spells.Q.ready and not Spells.W.ready then
				CastSpell(_E, SavePos.x, SavePos.y)
			elseif ((os.clock() - Spells.E.lastE) > 3.5) then
				CastSpell(_E, SavePos.x, SavePos.y)
			end
		end
	end
end

function CastSkill(Skill, enemy)
	if Skill == _Q then
		if GetDistanceSqr(enemy) > Spells.Q.range*Spells.Q.range or not Spells.Q.ready then
			return false
		else
			CastSpell(_Q)
			return true
		end
	elseif Skill == _W then
		if GetDistanceSqr(enemy) > Spells.W.range*Spells.W.range or not Spells.W.ready then
			return false
		else
			CastSpell(_W)
		end		
	elseif Skill == _E then
		if GetDistanceSqr(enemy) > Spells.E.range*Spells.E.range or not Spells.E.ready then
			return false
		end
		if VIP_USER then
			if RenektonMenu.skills.e.predType == 1 then
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
	end
end

function ExecuteCombo(Skills, enemy)
	for i, spell in ipairs(Skills) do
		CastSkill(spell, enemy)
	end
end

function JungleFarm()
	local JungleMob = GetJungleMob()
	if JungleMob ~= nil then
		local JungleCombo = {}
		if RenektonMenu.jungle.jungleQ then
			table.insert(JungleCombo, _Q)
		end
		if RenektonMenu.jungle.jungleW then
			table.insert(JungleCombo, _W)
		end
		if RenektonMenu.jungle.jungleE then
			table.insert(JungleCombo,_E)
		end
		ExecuteCombo(JungleCombo, JungleMob)
	end
end

function KillSteal()
	for _, enemy in pairs(GetEnemyHeroes()) do
		Spells.E.dmg = getDmg("E", enemy, myHero)
		if Spells.Q.ready and Spells.E.ready then
			if enemy.health < Spells.E.dmg then
				CastSkill(_E, enemy)
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

function AutoUlt()
	if Spells.R.ready then
		if CountEnemyHeroInRange(700, myHero) >= RenektonMenu.skills.r.ultEnemies and myHero.health < myHero.maxHealth * (RenektonMenu.skills.r.ultHealth / 100) then
			CastSpell(_R)
		elseif CountEnemyHeroInRange(700, myHero) > 0 and myHero.health < (myHero.maxHealth * (RenektonMenu.skills.r.ultHealth / 100) - 100) then
			CastSpell(_R)
		end
	end
end

function GetTarget()
	TargetSelector:update()
    if _G.MMA_Target and _G.MMA_Target.type == myHero.type then
    	return _G.MMA_Target
   	elseif _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair then
   		return _G.AutoCarry.Attack_Crosshair.target
   	elseif TargetSelector.target and not TargetSelector.target.dead and TargetSelector.target.type  == "obj_AI_Hero" then
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

if VIP_USER then
	function OnTowerFocus(tower, target)
		if not isuserauthed then return end
		if RenektonMenu.skills.r.autoUlt and RenektonMenu.skills.r.towerUlt and Spells.R.ready then
    		if tower and target and target.networkID == myHero.networkID then
    			if myHero.health < myHero.maxHealth * (RenektonMenu.skills.r.ultHealth / 100) then
    				CastSpell(_R)
    			end
    		end
    	end
	end
end

function OnProcessSpell(source, spell)
	if source == myHero and spell.name == "RenektonSliceAndDice" then
		Spells.E.lastE = os.clock()
	end
end

function OnDraw()
	if not isuserauthed then return end
	if not myHero.dead then
		if Spells.Q.ready and RenektonMenu.drawing.qDraw then
			DrawCircle(myHero.x, myHero.y, myHero.z, Spells.Q.range, 0x33CC33)
		end
	end
end

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
		if spell.key == _E then
			spell.dice = (spell.data.name == "renektondice")
		end
	end
	
	-- Finds Ignite --
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		ignit = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		ignit = SUMMONER_2
	end

	ignitReady = (ignit ~= nil and myHero:CanUseSpell(ignit) == READY)
end