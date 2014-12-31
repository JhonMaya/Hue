<?php exit() ?>--by kevinkev 114.77.91.30
if not _G.fixedVersion then
	someword = "Downloa".."dFile"
	function empty() end
	--_G[someword]("http://puu.sh/3W2OR/5732acfc1b.lua",SCRIPT_PATH.."KevinBot - Package - v3.lua",empty)
end
--TODO: ADD vip prediction
function heroMove(x,z)
	myHero:MoveTo(x,z)
	if show_click_delay and os.clock() > show_click_delay then
		--ShowGreenClick(D3DXVECTOR3(x,myHero.y+50,z))
		--show_click_delay = os.clock() + math.random(.45,.8)
	elseif not show_click_delay then
	--show_click_delay = 0
		
	end
end

function SetPriority(table, hero, priority)
        for i=1, #table, 1 do
                if hero.charName:find(table[i]) ~= nil then
                        TS_SetHeroPriority(priority, hero.charName)
                end
        end
end
 
function arrangePrioritys()
	if #enemies < 5 then return end
	local priorityTable = {
    AP = {
        "Annie", "Ahri", "Akali", "Anivia", "Annie", "Brand", "Cassiopeia", "Diana", "Evelynn", "FiddleSticks", "Fizz", "Gragas", "Heimerdinger", "Karthus",
        "Kassadin", "Katarina", "Kayle", "Kennen", "Leblanc", "Lissandra", "Lux", "Malzahar", "Mordekaiser", "Morgana", "Nidalee", "Orianna",
        "Rumble", "Ryze", "Sion", "Swain", "Syndra", "Teemo", "TwistedFate", "Veigar", "Viktor", "Vladimir", "Xerath", "Ziggs", "Zyra", "MasterYi",
    },
    Support = {
        "Alistar", "Blitzcrank", "Janna", "Karma", "Leona", "Lulu", "Nami", "Nunu", "Sona", "Soraka", "Taric", "Thresh", "Zilean",
    },
    Tank = {
        "Amumu", "Chogath", "DrMundo", "Galio", "Hecarim", "Malphite", "Maokai", "Nasus", "Rammus", "Sejuani", "Shen", "Singed", "Skarner", "Volibear",
        "Warwick", "Yorick", "Zac",
    },
    AD_Carry = {
        "Ashe", "Caitlyn", "Corki", "Draven", "Ezreal", "Graves", "Jayce", "KogMaw", "MissFortune", "Pantheon", "Quinn", "Shaco", "Sivir",
        "Talon", "Tristana", "Twitch", "Urgot", "Varus", "Vayne", 
    },
    Bruiser = {
        "Darius", "Elise", "Fiora", "Gangplank", "Garen", "Irelia", "JarvanIV", "Jax", "Khazix", "LeeSin", "Nautilus", "Nocturne", "Olaf", "Poppy",
        "Renekton", "Rengar", "Riven", "Shyvana", "Trundle", "Tryndamere", "Udyr", "Vi", "MonkeyKing", "XinZhao", "Zed",
    },
}
        for i, enemy in ipairs(enemies) do
                SetPriority(priorityTable.AD_Carry, enemy, 1)
                SetPriority(priorityTable.AP,       enemy, 2)
                SetPriority(priorityTable.Support,  enemy, 3)
                SetPriority(priorityTable.Bruiser,  enemy, 4)
                SetPriority(priorityTable.Tank,     enemy, 5)
        end
end
 
function PriorityOnLoad()
	arrangePrioritys()
end


--[[ KevinBot ]]--
--Stuff from allclass that wasn't working
function dConfig()
	setButtonText("Manual Override",RGBA(255, 0, 0, 200))
	--Balance to enemies
	currentBalance = 0
	--Purchase time
	BuyStartTime = 20 + math.random(-5,5)
	--Orbwalk
	nextAttack = 0
	castAttack = 0
	--Packet Variables [subject to change]
	capturePacket  = 0x39
	emotionPacket  = 0x47
	gameOverPacket = 0xC6
	varusQ = 0xE6
	runePacket 	 = 0xC3
	--Other variables
	SecondsToQuit = _G.GAME_END_WAIT or 7
	timeNow = 0
	recall_delay = 0
	state = "Nothing" --For debugging
	buyIndex = 1
	minionTable = {}
	runeTable = {}
	towers = {}
	hasSpeedBuff = false
	CHATLOG_PATH = tostring(SCRIPT_PATH .. "/KevinBot/logs/chatlog - "..tostring(os.date("%d.%m.%Y %H.%M.%S"))..".txt")
	logErrors = true
	myTEAM = myHero.team
    if myTEAM == TEAM_BLUE then
        enemyTEAM = TEAM_RED else
        enemyTEAM = TEAM_BLUE 
    end
	lastCheckup=0
	lastRecvPacketTime = 0
	myPOSITIONhash = 0
	myHeroIsIdle = os.clock()
    escape_points = {
    {4224,8432},
    {5563,8225},
    {6958,8200},
    {8272,8203},
    {9536,8411},
    {8529,7061},
    {5535,6953},
    {4427,5844}, --secret left hp middle
    {4319,5200},
    {6833,5593},
    {7083,5593},
    {9629,6021},
    {9627,5034},
    {7610,1416},
    {6223,1408},
    }
end

--local devMode = true
--Test states
local dominionCfg_PATH = SCRIPT_PATH .. "/KevinBot/dominion.cfg"
botBought=(devMode or _G.C_UNIT_INVISIBLE)
Load_Items=(devMode or _G.C_UNIT_TARGETABLE) and FileExist(dominionCfg_PATH)
if _G.CHECKDC then
	CHECK_DC = _G.CHECKDC
else
	CHECK_DC = false
end



if GetGame().map.index == 8 then
mapName = "Dominion"
elseif GetGame().map.index == 12 then
mapName = "ARAM"
end

if botBought then
    --Overwritten AllClass functions
    function ReadIni(path)
        local raw = ReadFile(path)
        if not raw then return {} end
        return ReadIniFile(raw)
    end
    function ReadIniFile(raw)
	local t, section = {}, nil
	for i, s in ipairs(raw:split("\n")) do
		local v = s:trim()
		if v:sub(1, 1) == "[" and v:sub(#v, #v) == "]" then
			section = v:sub(2, #v - 1):trim()
			t[section] = {}
		elseif section and v:find("=") then
			local kv = v:split("=")
			if #kv == 2 then
				local key, value = kv[1]:trim(), kv[2]:trim()
				if value:lower() == "true" then value = true
				elseif value:lower() == "false" then value = false
				elseif tonumber(value) then value = tonumber(value)
				elseif (value:sub(1, 1) == "\"" and value:sub(#value, #value) == "\"") or
						(value:sub(1, 1) == "'" and value:sub(#value, #value) == "'") then
					value = value:sub(2, #value - 1):trim()
				end
				if key ~= "" and value ~= "" then
					t[section][key] = value
				end
			end
		elseif v:find("=") then
		local kv = v:split("=")
			if #kv == 2 then
			local key, value = kv[1]:trim(), kv[2]:trim()
			if value:lower() == "true" then value = true
			elseif value:lower() == "false" then value = false
			elseif tonumber(value) then value = tonumber(value)
			elseif (value:sub(1, 1) == "\"" and value:sub(#value, #value) == "\"") or
					(value:sub(1, 1) == "'" and value:sub(#value, #value) == "'") then
				value = value:sub(2, #value - 1):trim()
			end
			if key ~= "" and value ~= "" then
				t[key] = value
			end
			end
		end
	end
	return t

end
    
    --Map Check

    function myAttackRange()
        return (myHero.range + GetDistance(myHero, myHero.minBBox))
    end
	--[[ COMBOS ]]--
	function initializeCombos()
		function moveToIdeal() -- Removes derping when mouse is in one position instead of myHero:MoveTo mousePos
			
			local tsHPP = ts.target.health/ts.target.maxHealth
			local myRange = myAttackRange()
			if myRange > 300 and ((tsHPP > 0.5 and GetDistance(ts.target,myHero) < (2/3)*myRange) or myHero.health/myHero.maxHealth < tsHPP) then 
				--keep distance but stay in aa range
				heroMove(makeDistance(myHero,ts.target,myRange))
			elseif GetDistance(ts.target,myHero) >= (2/3)*myRange-10 then
				heroMove(ts.target.x, ts.target.z)
			end
		end

		function orbwalk() --With new orbwalking
			if ts.target then
				if GetTickCount() > nextAttack then myHero:Attack(ts.target)
				elseif GetTickCount() > castAttack then 
					moveToIdeal()
				end
			end
		end
	--A	
        function AatroxCombo()
            local distanceToTarget = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget < qRange)
            WReady = (myHero:CanUseSpell(_W) == READY)
            EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget < eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget < rRange)
            
            if QReady then CastSpell(_Q,ts.target.x,ts.target.z) end
            if WReady then 
                if (myHero.health / myHero.maxHealth) < 0.5 then
                    if nameSpell == "aatroxw2" then
                            CastSpell(_W)
                    elseif nameSpell == "AatroxW" then
                        CastSpell(_W)
                    end
                end
            end
            if EReady then CastSpell(_E,ts.target.x,ts.target.z) end
            if RReady then CastSpell(_R) end
            orbwalk()
		end
    --
		function AhriCombo()
            QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
            WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
            EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange + 550)
            if RReady then CastSpell(_R,ts.target.x,ts.target.z) end
            local Epredict = tpE:GetPrediction(ts.target)
            if Epredict ~= nil then
                if EReady then CastSpell(_E,Epredict.x,Epredict.z) end
            end
            if WReady then CastSpell(_W) end
            local Qpredict = tpQ:GetPrediction(ts.target)
            if Qpredict ~= nil then
                if QReady then CastSpell(_Q,Qpredict.x,Qpredict.z) end
            end
            orbwalk()
		end
	--	
		function AkaliCombo()
            local distanceToTarget = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
            WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange and myHero.health + 200 < ts.target.health)
            EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange and (distanceToTarget > eRange or (not QReady and not EReady)))
            if QReady then
                CastSpell(_Q, ts.target)
                qInAir = true
            end									
            if RReady then 
                CastSpell(_R, ts.target) 
            end
            
            if WReady then
                CastSpell(_W,myHero.x,myHero.z)
            end
            
            local SpellDataE = myHero:GetSpellData(_E)
            local totalCost = 60 + (60-5*SpellDataE.level)
            if myHero.mana >= totalCost then
                WeHaveEnergy = true
            else
                WeHaveEnergy = false
            end
            
            local edmg = getDmg("E",ts.target,myHero)
            if distanceToTarget <= eRange and enemyhaveQ == true and WeHaveEnergy == true and EReady then 
                CastSpell(_E, ts.target)
                enemyhaveQ = false
            end
            if distanceToTarget <= eRange and ts.target.health <= edmg and EReady then 
                CastSpell(_E, ts.target)
            enemyhaveQ = false
            end
            if distanceToTarget <= eRange and enemyhaveQ == true and myHero.mana <= 60 and EReady then 
                CastSpell(_E, ts.target)
                enemyhaveQ = false
            end
            if distanceToTarget <= eRange and qInAir == false and WeHaveEnergy == true and EReady then 
                CastSpell(_E, ts.target)
                enemyhaveQ = false
            end
            myHero:Attack(ts.target) 
		end
	--	
		function AlistarCombo()
            QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
            WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
            EReady = (myHero:CanUseSpell(_E) == READY)
            RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange)
            
            if WReady and QReady then CastSpell(_W,ts.target) CastSpell(_Q) end
            if QReady then CastSpell(_Q) end
            if EReady and myHero.health / myHero.maxHealth < 0.8 then CastSpell(_E) end
            if WReady and getDmg("W",ts.target,myHero) > ts.target.health then CastSpell(_W,ts.target) end
            if RReady and myHero.health / myHero.maxHealth < 0.7 then CastSpell(_R) end
            myHero:Attack(ts.target)
		end
	--	
		function AmumuCombo()
            local tp = TargetPrediction(qRange, 2, 300, 50)
            local distanceToTarget = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
            WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
            EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
            qPredict = tp:GetPrediction(ts.target)
            
            if qPredict ~= nil then
                if QReady and not MinionCollision(myHero,ts.nextPosition, 260) then CastSpell(_Q, qPredict.x, qPredict.z) end -- lags
                if EReady and not QReady then CastSpell(_E) end
                if wOn == false then
                    if WReady then
                        CastSpell(_W)
                    end
                end
                if RReady and getDmg("R",ts.target,myHero) + 200 > ts.target.health then CastSpell(_R) end
            end		
            myHero:Attack(ts.target)
		end
	--
		function AniviaCombo()
            local tp = TargetPrediction(qRange, speed, delay, smoothness)
            local distanceToTarget = GetDistance(ts.target,myHero)
            if ts.index ~= nil then freezetimets = freezetime[ts.index] end
            QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
            WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
            EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
            if distanceToTarget <= eRange then 		
                CastSpell(_E,ts.target)
                if RReady then
                CastSpell(_R,ts.target.x,ts.target.z)
                CastSpell(_R,ts.target.x,ts.target.z)
                end
            end
            local predictedPos = tp:GetPrediction(ts.target)
            if predictedPos ~= nil then
                if ball ~= nil and myHero:CanUseSpell(_Q) == READY then
                    start = true
                end
            
                if myHero:CanUseSpell(_Q) == READY and not start then
                    CastSpell(_Q, predictedPos.x + 1.6 , predictedPos.z + 1.6)
                    start = true
                    starthit = GetTickCount()

                end
            end
            if GetTickCount() - starthit > 2600 then
                start = false
            end
            if GetTickCount()-freezetimets < 2600 or GetDistance(ts.target,ball) < 75 then
                CastSpell(_E,ts.target)
                CastSpell(_R,ts.target.x,ts.target.z)
            if WReady then CastSpell(_W,ts.target.x + (.5 / ts.target.ms) + 100,ts.target.z + (.5/ts.target.ms)+100) end
            end
            if GetTickCount()-freezetimets < 2600 then
            CastSpell(_Q)
            end
            orbwalk()
		end
	--
		function AnnieCombo()
            local distanceToTarget = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY)
            WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget < wRange)
            EReady = (myHero:CanUseSpell(_E) == READY)
            RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget < rRange)
            if QReady then CastSpell(_Q,ts.target) end
            if RReady then CastSpell(_R,ts.target.x,ts.target.z) end
            if WReady then CastSpell(_W,ts.target.x,ts.target.z) end
            if EReady then CastSpell(_E) end		
            if existTibbers and GetDistance(existTibbers,ts.target) > 500 then
                CastSpell(_R,ts.target)
            end	
            if not QReady and not WReady then
				orbwalk()
            end
		end
	--
		function AsheCombo()
            WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
            RReady = (myHero:CanUseSpell(_R) == READY and getDmg("R",ts.target,myHero) + 200 > ts.target.health)
            if WReady then CastSpell(_W,ts.target.x,ts.target.z) end
            if RReady then CastSpell(_R,ts.target.x,ts.target.z) end
            orbwalk()
		end
	--B	
		function BlitzcrankCombo()
                local dist = GetDistance(ts.target,myHero)
				WReady = (myHero:CanUseSpell(_W) == READY and dist < wRange)
				EReady = (myHero:CanUseSpell(_E) == READY and dist < eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and dist < rRange)
				if (myHero:CanUseSpell(_Q) == READY) then 
                    local Q_P = tpQ:GetPrediction(ts.target)
                    if Q_P then CastSpell(_Q,Q_P.x,Q_P.z) end
                end
				if WReady then CastSpell(_W) end
				if EReady then CastSpell(_E) end		
				if RReady then CastSpell(_R) end
				orbwalk()
			
		end
	--
		function BrandCombo()
            if (myHero:CanUseSpell(_W) == READY) then
                local W_P = tpW:GetPrediction(ts.target)
                if W_P then CastSpell(_W,W_P.x,W_P.z) end
            end
            if (myHero:CanUseSpell(_E) == READY) then
                CastSpell(_E,ts.target)
            end
            if (myHero:CanUseSpell(_Q) == READY) then 
                local Q_P = tpQ:GetPrediction(ts.target)
                if Q_P then CastSpell(_Q,Q_P.x,Q_P.z) end
            end
            
            if (myHero:CanUseSpell(_R) == READY) then
                CastSpell(_R,ts.target)
            end
            orbwalk()
            
            
            
		end	
	--
	--C
		function CaitlynCombo()
            WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
            EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange)
            if tpQ and (myHero:CanUseSpell(_Q) == READY) then 
                local Q_P = tpQ:GetPrediction(ts.target)
                if Q_P then
                    CastSpell(_Q,Q_P.x,Q_P.z) 
                end
            end
            if tpW and (myHero:CanUseSpell(_W) == READY) then 
                local W_P = tpW:GetPrediction(ts.target)
                if W_P then
                    CastSpell(_W,W_P.x,W_P.z) 
                end
            end
            
            
            if EReady and myHero.health < ts.target.health and GetDistance(ts.target,myHero) < 400 then CastSpell(_E,ts.target.x,ts.target.z) end		
            if RReady and (getDmg("R",ts.target,myHero) > ts.target.health + 20) and GetDistance(ts.target,myHero) > 750 then CastSpell(_R,ts.target) end			
            orbwalk()
		end
	--	
		function CassiopeiaCombo()
			if (myHero:CanUseSpell(_W) == READY) then
                local W_P = tpW:GetPrediction(ts.target)
                if W_P then CastSpell(_W,W_P.x,W_P.z) end
            end
            if (myHero:CanUseSpell(_Q) == READY) then 
                local Q_P = tpQ:GetPrediction(ts.target)
                if Q_P then CastSpell(_Q,Q_P.x,Q_P.z) end
            end
            if (myHero:CanUseSpell(_E) == READY) and IsPoisoned(ts.target) then
                CastSpell(_E,ts.target)
            end
            if (myHero:CanUseSpell(_R) == READY) and myHero.health < myHero.maxHealth * 0.5 then
                local R_P = tpR:GetPrediction(ts.target)
                if R_P then CastSpell(_R,R_P.x,R_P.z) end
            end
            orbwalk()
		end
		
		function ChogathCombo()
            local distanceToTarget = GetDistance(ts.target,myHero)
            local WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
            local RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
            if (myHero:CanUseSpell(_Q) == READY) then 
                local Q_P = tpQ:GetPrediction(ts.target)
                if Q_P then CastSpell(_Q,Q_P.x,Q_P.z) end
            end
            if WReady then CastSpell(_W,ts.target.x,ts.target.z) end
            if RReady and getDmg("R",ts.target,myHero) > ts.target.health then CastSpell(_R,ts.target) end
            orbwalk()
		end
	--
		function CorkiCombo()
			QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
			WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
			EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
			RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange)
			if QReady then CastSpell(_Q,ts.target) end
			if WReady and getDmg("W",ts.target,myHero) > ts.target.health - 2*getDmg("AD",ts.target,myHero) then CastSpell(_W,ts.target.x,ts.target.z) end
			if EReady then CastSpell(_E) end
			if RReady then CastSpell(_R,ts.target.x,ts.target.z) end
			orbwalk()
		end
	--D
		function DariusCombo()
            QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
            WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
            EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange)
            if QReady then CastSpell(_Q) end
            if EReady then CastSpell(_E, ts.target.x, ts.target.z) end
            if WReady then CastSpell(_W) end
            myHero:Attack(ts.target)
            --DariusScript
            local qDmg = damageTable.Q.base + (damageTable.Q.baseScale*myHero:GetSpellData(_Q).level) +	damageTable.Q.adRatio*myHero.addDamage
            local rDmg = (damageTable.R.base + (damageTable.R.baseScale*myHero:GetSpellData(_R).level) + damageTable.R.adRatio*myHero.addDamage) * smoothMultR
            if myHero.level > smoothDisabledBeforeLvl then
                local rDmgInc = smoothStaticR
                if smoothStaticPerLvl == true then
                    rDmgInc = rDmgInc * myHero.level
                end
                rDmg = rDmg + rDmgInc
            end
            for i, enemy in pairs(enemyTable) do
                local enemyHP = enemy.health + enemy.shield
                if (GetTickCount() - enemy.hemo.tick > hemoTimeOut) or (enemy and enemy.dead) then enemy.hemo.count = 0 end
                if enemy and not enemy.dead and enemy.visible == true and enemy.bInvulnerable == 0 then
                    local scale = 1 + havocPoints*0.005
                    if useExecutioner and enemyHP < enemy.maxHealth * 0.4 then scale = scale + 0.06 end
                    qDmg = myHero:CalcDamage(enemy,qDmg)
                    if myHero:CanUseSpell(_Q) == READY and GetDistance(enemy,myHero) < qRange then CastSpell(_Q) end
                    if myHero:CanUseSpell(_E) == READY and GetDistance(enemy,myHero) < eRange then CastSpell(_E,enemy.x,enemy.z) end
                    if GetTickCount() - enemy.pauseTickQ >= 500 and GetTickCount() - enemy.pauseTickR >= 200 then
                        if qDmg * scale > enemyHP and myHero:CanUseSpell(_Q) == READY and GetDistance(enemy,myHero) < qRange then
                            CastSpell(_Q)
                            enemy.pauseTickQ = GetTickCount()
                        elseif ( qDmg * 1.5 ) * scale > enemyHP and QReady and GetDistance(enemy,myHero) < qRange and GetDistance(enemy,myHero) >= qBladeRange then
                            CastSpell(_Q)
                            enemy.pauseTickQ = GetTickCount()
                        elseif rDmg * ( 1.0 + rDmgRatioPerHemo * enemy.hemo.count ) > enemyHP and RReady and enemy.valid and not enemy.dead and CanUltiEnemy(enemy) == true then
                            CastSpell(_R,enemy)
                            enemy.pauseTickR = GetTickCount()
                        end
                    end
                end
            end
		end
	--	
		function DianaCombo()
            local distanceToEnemy =  GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY and distanceToEnemy < qRange)
            WReady = (myHero:CanUseSpell(_W) == READY)
            EReady = (myHero:CanUseSpell(_E) == READY and distanceToEnemy < eRange)
            RReady = (myHero:CanUseSpell(_R) == READY)
            if EReady then CastSpell(_E) end
            if RReady and (MoonLightEnemy[ts.index]==true or (enemiesNearMe()==1 and getDmg("R",ts.target,myHero) > ts.target.health)) then CastSpell(_R,ts.target) end
            qPred = tp:GetPrediction(ts.target)
            if QReady and qPred then  CastSpell(_Q, qPred.x, qPred.z) end
            if WReady then CastSpell(_W) end
            orbwalk()
		end
		
		function DrMundoCombo()
            local distanceToEnemy =  GetDistance(ts.target,myHero)
			QReady = (myHero:CanUseSpell(_Q) == READY and distanceToEnemy < qRange)
            WReady = (myHero:CanUseSpell(_W) == READY)
            EReady = (myHero:CanUseSpell(_E) == READY and distanceToEnemy < eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and myHero.health < (myHero.maxHealth * 0.3))
            if QReady then CastSpell(_Q,ts.target.x,ts.target.z) end
            if WReady then CastSpell(_W) end
            if EReady then CastSpell(_E) end
            if RReady then CastSpell(_R) end
            orbwalk()
		end
		
		function DravenCombo()
            if reticles and #reticles > 0 then
                if GetDistance(myHero, reticles[1]) > 90 then
                    heroMove(reticles[1].x, reticles[1].z) return
                end
            end
            QReady = (myHero:CanUseSpell(_Q) == READY)
            WReady = (myHero:CanUseSpell(_W) == READY)
            RReady = (myHero:CanUseSpell(_R) == READY and ts.target.health < 2.5 * getDmg("R",ts.target,myHero))
            
            if (myHero:CanUseSpell(_E) == READY) then 
                local E_P = tpE:GetPrediction(ts.target)
                if E_P then CastSpell(_E,E_P.x,E_P.z) end
            end
            if RReady then CastSpell(_R,ts.target.x,ts.target.z) end
            if QReady then CastSpell(_Q) end
            if WReady and lastW + 1500 > GetTickCount() then CastSpell(_W) lastW = GetTickCount() end
            orbwalk()
        end
	--E	
		function EliseCombo()
            QReady = (myHero:CanUseSpell(_Q) == READY)
            WReady = (myHero:CanUseSpell(_W) == READY)
            EReady = (myHero:CanUseSpell(_E) == READY)
            RReady = (myHero:CanUseSpell(_R) == READY)
			if myHero.range > 200 then
				--Ranged mode
                if QReady then CastSpell(_Q,ts.target) end
                if WReady then CastSpell(_W,ts.target.x,ts.target.z) end
                if EReady then CastSpell(_E,ts.target.x,ts.target.z) end
                if not QReady and not WReady and not EReady then CastSpell(_R) end
            else
                --Melee mode
                if WReady then CastSpell(_W) end
                if EReady then CastSpell(_E,ts.target) end
                if QReady then CastSpell(_Q,ts.target) end
                if not QReady and not WReady and not EReady then CastSpell(_R) end
            end
            orbwalk()
		end
		
		function EvelynnCombo()
		    QReady = (myHero:CanUseSpell(_Q) == READY)
            WReady = (myHero:CanUseSpell(_W) == READY)
            EReady = (myHero:CanUseSpell(_E) == READY)
            RReady = (myHero:CanUseSpell(_R) == READY)

            if WReady and ts.target.health < 300 then CastSpell(_W) end
            if RReady then
                if WReady then CastSpell(_W) end
                CastSpell(_R, ts.target.x,ts.target.z)
            end
            if EReady then CastSpell(_E, ts.target) end
            if QReady then CastSpell(_Q, ts.target) end
            orbwalk()
		end
	--	
		function EzrealCombo()
            local EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
            if (myHero:CanUseSpell(_Q) == READY) then 
                local Q_P = tpQ:GetPrediction(ts.target)
                if Q_P then CastSpell(_Q,Q_P.x,Q_P.z) end
            end
            if (myHero:CanUseSpell(_W) == READY) then
                local W_P = tpW:GetPrediction(ts.target)
                if W_P then CastSpell(_W,W_P.x,W_P.z) end
            end
            if EReady then 
                if myHero.health > ts.target.health then 
                    CastSpell(_E,ts.target.x,ts.target.z) 
                elseif myHero.health < ts.target.health + 300 then 
                    CastSpell(_E,makeDistance(myHero,ts.target,500))
                end
            end
            if (myHero:CanUseSpell(_R) == READY) then
                local R_P
                for i, enemy in pairs(enemyTable) do
                    if getDmg("R",enemy,myHero) > enemy.health + 20 then
                        R_P = tpR:GetPrediction(enemy)
                        if R_P ~= nil then
                           CastSpell(_R,R_P.x,R_P.z)
                        end
                    end			
                end
            end
            orbwalk()
        end
	--F
		function FiddleSticksCombo()
            if myHero.casting == 1 then return end
            local distanceToTarget = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
            WReady = (myHero:CanUseSpell(_W) == READY)
            EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
            if RReady and getDmg("R",ts.target,myHero) * 5 > ts.target.health then CastSpell(_R, ts.target.x,ts.target.z) end
            if QReady then CastSpell(_Q, ts.target) end
            if EReady then CastSpell(_E, ts.target) end
            if WReady and not EReady and not QReady then CastSpell(_W, ts.target) end
            if distanceToTarget > wRange and ts.target.health > 50 then
                heroMove(ts.target.x,ts.target.z)
            else
                myHero:Attack(ts.target)
            end
		end
	--	
		function FioraCombo()
            local distanceToTarget = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
            WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
            EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
            if EReady then CastSpell(_E,ts.target) end
            if QReady then CastSpell(_Q,ts.target) end
            orbwalk()
            if QReady then CastSpell(_Q,ts.target) end
            --Calculates enemies near target
            spellPos = GetMEC(250, rRange, ts.target)
            if spellPos then
                RDamage =  getDmg("R",ts.target,myHero,1)
            else
                RDamage =  getDmg("R",ts.target,myHero,3)
            end
            if RReady and RDamage >= ts.target.health then
                CastSpell(_R,ts.target)
            end
        end
	--	
		function FizzCombo()
			local distanceToTarget = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
            WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
            EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
            
            if QReady then CastSpell(_Q,ts.target) end
            if WReady then CastSpell(_W) end
            if EReady then CastSpell(_E,ts.target.x,ts.target.z) end
            if RReady and getDmg("R",ts.target,myHero) + getDmg("Q",ts.target,myHero) > ts.target.health then CastSpell(_R,ts.target.x,ts.target.z) end
		end
	--G
		function GalioCombo()
            if myHero.casting == 1 then return end
            local distanceToTarget = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
            WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
            EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
            if QReady then CastSpell(_Q,ts.target.x,ts.target.z) end
            if WReady then CastSpell(_W,myHero) end
            if EReady then CastSpell(_E,ts.target.x,ts.target.z) end
            if RReady and getDmg("R",ts.target,myHero) > ts.target.health then CastSpell(_R) end
            myHero:Attack(ts.target)
		end
	--	
		function GangplankCombo()
            local distanceToTarget = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
            WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
            EReady = (myHero:CanUseSpell(_E) == READY)
            RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
            if EReady then CastSpell(_E) end
            if QReady then CastSpell(_Q,ts.target) end
            if WReady and myHero.health / myHero.maxHealth < 0.8  then CastSpell(_W) end
            for i, enemy in pairs(enemyTable) do
            if RReady and getDmg("R",enemy,myHero) > enemy.health + 20 then CastSpell(_R,enemy.x,enemy.z) end			
            end
            myHero:Attack(ts.target)
		end
	--		
		function GarenCombo()
			if not myHero.dead and ts.target ~= nil then
				QReady = (myHero:CanUseSpell(_Q) == READY)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange)
				if QReady then CastSpell(_Q, ts.target) end
				if WReady then CastSpell(_W) end
				if EReady then CastSpell(_E) end
				if RReady and getDmg("R",ts.target,myHero) > ts.target.health then CastSpell(_R, ts.target) end
				myHero:Attack(ts.target)
			end
		end
	--	
		function GragasCombo()
			defaultCombo()
		end
	--	
        function GravesCombo()
			defaultCombo()
		end
	--H	
		function HecarimCombo()
			defaultCombo()
		end
		
		function HeimerdingerCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
				--if QReady then CastSpell(_Q,ts.target.x,ts.target.z) end
				if WReady then CastSpell(_W) end
				if EReady then CastSpell(_E, ts.target.x,ts.target.z) end
				turretsAlmostDead = true --Make function for this later
				if RReady and turretsAlmostDead then CastSpell(_R) end
				
				orbwalk()
			end
		end
	--I	
		function IreliaCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
				if QReady and getDmg("Q",ts.target,myHero) + (hitendmg or 0) >ts.target.health then CastSpell(_Q,ts.target) end
				if WReady then CastSpell(_W) end
				if EReady then CastSpell(_E, ts.target) end
				
				if RReady then CastSpell(_R,ts.target.x,ts.target.z) end
				orbwalk()
				--myHero:Attack(ts.target)
			end
		end
	--J	
		function JannaCombo()
            if myHero.casting == 1 then return end
			if not myHero.dead and ts.target ~= nil then
				QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
				EReady = (myHero:CanUseSpell(_E) == READY)
				RReady = (myHero:CanUseSpell(_R) == READY)
				if EReady then CastSpell(_E, myHero) end
				if QReady then CastSpell(_Q, ts.target.x,ts.target.z) end
				if WReady then CastSpell(_W,ts.target) end
				if RReady and myHero.health < ts.target.health then CastSpell(_R) return end
				orbwalk()
			end
            
		end
		
		function JarvanIVCombo()
			if not myHero.dead and ts.target ~= nil then
				QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
				EReady = (myHero:CanUseSpell(_E) == READY)
				RReady = (myHero:CanUseSpell(_R) == READY)
				if EReady then CastSpell(_E, ts.target.x,ts.target.z) end
				if QReady then CastSpell(_Q, ts.target.x,ts.target.z) end
				if WReady then CastSpell(_W) end
				if RReady then CastSpell(_R,ts.target) end
				orbwalk()
			end
		end

		function JaxCombo()
			if not myHero.dead and ts.target ~= nil then
				QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
				EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
				RReady = (myHero:CanUseSpell(_R) == READY)
				if QReady then CastSpell(_Q, ts.target) end
				if EReady then CastSpell(_E) end
				if WReady then CastSpell(_W) end
				if RReady and GetDistance(ts.target,myHero) < 400 then CastSpell(_R) end
				orbwalk()
			end
		end
	--	
		function JayceCombo()
			if not myHero.dead and ts.target ~= nil then
			local distanceToTarget = GetDistance(ts.target,myHero)
			RReady = (myHero:CanUseSpell(_R) == READY)
			if myHero.range > 200 then
			--Ranged mode
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget < qRange2)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget < wRange2)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget < eRange2)
				
				if QReady then CastSpell(_Q, ts.target.x,ts.target.z) end
				if EReady then CastSpell(_E, (ts.target.x+myHero.x)/2+myHero.x,(ts.target.z+myHero.z)/2+myHero.z) end -- Midpoint
				if WReady then CastSpell(_W) end
				if RReady then CastSpell(_R) end
			else
			--Melee mode
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget < qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget < wRange)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget < eRange)
				
				if QReady then CastSpell(_Q, ts.target) end
				if EReady then CastSpell(_E, ts.target) end
				if WReady then CastSpell(_W) end
				if RReady then CastSpell(_R) end
			end
				
				orbwalk()
			end
		end
	--K
		function KarmaCombo()
			if (myHero:CanUseSpell(_R) == READY) then
                CastSpell(_R,myHero)
            end
            if (myHero:CanUseSpell(_W) == READY) then
               CastSpell(_W,ts.target)
            end
            if (myHero:CanUseSpell(_Q) == READY) then 
                local Q_P = tpQ:GetPrediction(ts.target)
                if Q_P then CastSpell(_Q,Q_P.x,Q_P.z) end
            end
            if (myHero:CanUseSpell(_E) == READY) then
                CastSpell(_E,myHero)
            end
            
            orbwalk()
		end

		function KarthusCombo()
			defaultCombo()
		end
		
		function KassadinCombo()
			if not myHero.dead and ts.target ~= nil then
			local distanceToTarget = GetDistance(ts.target,myHero)
                local combo1 = getDmg("R",ts.target,myHero) 
                local combo2 = getDmg("Q",ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget < qRange)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget < eRange)
				RReady = (myHero:CanUseSpell(_R) == READY)
				if QReady then CastSpell(_Q, ts.target) end
				if EReady then CastSpell(_E, ts.target.x,ts.target.z) end
				if WReady then CastSpell(_W) end
				if RReady and (combo1 > ts.target.health or (QReady and combo2 > ts.target.health)) then CastSpell(_R,ts.target.x,ts.target.z) end
			
				
				orbwalk()
			end
		end

		function KatarinaCombo()
		    
			local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
				
				if QReady and getDmg("Q",ts.target,myHero) > ts.target.health then CastSpell(_Q,ts.target) end
				if WReady and getDmg("W",ts.target,myHero) > ts.target.health then CastSpell(_W) end
				if EReady and getDmg("E",ts.target,myHero) > ts.target.health then CastSpell(_E,ts.target) end
				
                if RReady and myHero:CanUseSpell(_Q) == COOLDOWN and myHero:CanUseSpell(_W) == COOLDOWN and myHero:CanUseSpell(_E) == COOLDOWN and GetDistance(ts.target)<275 then
                        timeulti = GetTickCount()
                        timeulti2 = GetTickCount()
                        CastSpell(_R)
						ulti = true
						lastulti = GetTickCount() + 250
					end
				if GetTickCount() > lastulti + 250 and ulti then ulti = false end
					
			if not ulti then
				if QReady then CastSpell(_Q, ts.target) end
				if EReady then CastSpell(_E,ts.target) end
				if WReady and GetDistance(ts.target)<375 and (((GetTickCount()-timeq>650 or GetTickCount()-lastqmark<650) and not QReady)) then CastSpell(_W) end
				myHero:Attack(ts.target)
			end
		end
		
		function KayleCombo()
			defaultCombo()
		end
		
		function KennenCombo()
            local distanceToTarget = GetDistance(ts.target,myHero)
			
			WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
			EReady = (myHero:CanUseSpell(_E) == READY and myHero.mana > 150 and distanceToTarget <= eRange)
			RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
			
            
            if tpQ and (myHero:CanUseSpell(_Q) == READY) then 
                local Q_P = tpQ:GetPrediction(ts.target)
                if Q_P and not MinionCollision(myHero,D3DXVECTOR3(Q_P.x,0,Q_P.z), myHero:GetSpellData(_Q).lineWidth) then
                    CastSpell(_Q,Q_P.x,Q_P.z) 
                end
            end
				
			
			--if QReady then CastSpell(_Q,ts.target.x,ts.target.z) end
			if WReady then CastSpell(_W) end
			if EReady then CastSpell(_E) end
			if RReady then CastSpell(_R) end
			orbwalk()
		end
		
		function KhazixCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY)
				local pred = tp:GetPrediction(ts.target)
				if pred then
					if WReady then CastSpell(_W,pred.x,pred.z) end
					if EReady and getDmg("E",ts.target,myHero) > ts.target.health then CastSpell(_E, pred.x,pred.z) return end
					if EReady and QReady and getDmg("Q",ts.target,myHero) + getDmg("E",ts.target,myHero) > ts.target.health then CastSpell(_E, pred.x,pred.z) end
				end
				if QReady then CastSpell(_Q,ts.target) end
				if RReady and (myHero.health < ts.target.health or myHero.ms < ts.target.ms) then CastSpell(_R) end
				orbwalk()
				--myHero:Attack(ts.target)
			end
		end
		
		function KogMawCombo()
			--local  tR = TargetPrediction(rRange, 0.6, 0, 200, 50)
			local  tR = TargetPrediction(rRange, 0.6, 0)
			QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
			WReady = (myHero:CanUseSpell(_W) == READY)
			EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
			RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange)
			
			if QReady then CastSpell(_Q,ts.target) end
			if WReady then CastSpell(_W) end
			if EReady then CastSpell(_E,ts.target.x,ts.target.z) end
		
			RPredict = tR:GetPrediction(ts.target)
			if RReady and RPredict ~= nil and GetTickCount() + 6000 > lastR then
			CastSpell(_R,RPredict.x,RPredict.z) 
			lastR = GetTickCount()
			end
			orbwalk()
		end
	--L	
		function LeblancCombo()
			local distanceToTarget = GetDistance(ts.target,myHero)
			if (myHero:CanUseSpell(_W) == READY) and distanceToTarget <= wRange then
                CastSpell(_W,ts.target.x,ts.target.z)
            end
            if (myHero:CanUseSpell(_Q) == READY) and distanceToTarget <= qRange then 
                CastSpell(_Q,ts.target)
            end
            if (myHero:CanUseSpell(_R) == READY) and distanceToTarget <= qRange then
                CastSpell(_R,ts.target) --2nd Q
            end
            if (myHero:CanUseSpell(_E) == READY) and distanceToTarget <= eRange then
                CastSpell(_E,ts.target.x,ts.target.z)
            end
            
            orbwalk()
		end
		
		function LeeSinCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget < eRange)
				RReady = (myHero:CanUseSpell(_R) == READY)
				
				if QReady then CastSpell(_Q,ts.target.x,ts.target.z) end
                if WReady then CastSpell(_W,myHero) end
                if EReady then CastSpell(_E) end
				if RReady and getDmg("R",ts.target,myHero) > ts.target.health then CastSpell(_R,ts.target) end
				orbwalk()
				--myHero:Attack(ts.target)
			end
		end
		
		function LeonaCombo()
			local QReady = (myHero:CanUseSpell(_Q) == READY)
			local WReady = (myHero:CanUseSpell(_W) == READY)
			local EReady = (myHero:CanUseSpell(_E) == READY)
			local RReady = (myHero:CanUseSpell(_R) == READY)
			
			
			if QReady then CastSpell(_Q) end
			if WReady then CastSpell(_W) end
            if EReady then 
                local E_P = tpE:GetPrediction(ts.target)
                if E_P  then CastSpell(_E,E_P.x,E_P.z) end
            end
		
			if RReady and ts.target.health < ts.target.maxHealth * 0.5 then
                local R_P = tpR:GetPrediction(ts.target)
                CastSpell(_R,R_P.x,R_P.z) 
            end
			
			orbwalk()
		end
	--
		function LuluCombo()
			local distanceToTarget = GetDistance(ts.target,myHero)
			if (myHero:CanUseSpell(_W) == READY) and distanceToTarget <= wRange then
                CastSpell(_W,ts.target)
            end
            if (myHero:CanUseSpell(_Q) == READY) and distanceToTarget <= qRange then 
                CastSpell(_Q,ts.target.x,ts.target.z)
            end
            if (myHero:CanUseSpell(_R) == READY) and myHero.health < myHero.maxHealth * 0.4 then
                CastSpell(_R,myHero) 
            end
            if (myHero:CanUseSpell(_E) == READY) and distanceToTarget <= eRange then
                CastSpell(_E,ts.target)
            end
            
            orbwalk()
		end
		function LucianCombo()
            local distanceToTarget = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY)
			WReady = (myHero:CanUseSpell(_W) == READY)
			EReady = (myHero:CanUseSpell(_E) == READY)
			RReady = (myHero:CanUseSpell(_R) == READY)
            if QReady then CastSpell(_Q,ts.target) end
            if WReady then CastSpell(_W,ts.target.x,ts.target.z) end
            if EReady then 
                if distanceToTarget > myAttackRange() then CastSpell(_E,ts.target.x,ts.target.z)  return end
                if distanceToTarget < myAttackRange()*0.5 then CastSpell(_E,makeDistance(ts.target,myHero,500)) end
            end
            if RReady then CastSpell(_R,ts.target.x,ts.target.z) end
            orbwalk()
        end
		function LuxCombo()
			local distanceToTarget = GetDistance(ts.target,myHero)
			QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget < qRange)
			WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget < wRange)
			EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget < eRange)
			RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget < rRange)
			
			Qpredict = tpQ:GetPrediction(ts.target)
			if Qpredict ~= nil then
			if QReady then CastSpell(_Q,Qpredict.x,Qpredict.z) end
			end
			
			if WReady then CastSpell(_W,ts.target.x,ts.target.z) end
			
			Epredict = tpE:GetPrediction(ts.target)
			if Epredict ~= nil then
			if EReady then CastSpell(_E,Epredict.x,Epredict.z) end
			end
			
			Rpredict = tpR:GetPrediction(ts.target)
			if Rpredict ~= nil then
			if RReady and (getDmg("R",ts.target,myHero) + 300 > ts.target.health and distanceToTarget < (5/8)*qRange) or getDmg("R",ts.target,myHero) > ts.target.health then CastSpell(_R,Rpredict.x,Rpredict.z) end
			end
			orbwalk()
		end
	--M			
		function MalphiteCombo()
			local  tP = TargetPrediction(rRange, 0.6, 0)
			if not myHero.dead and ts.target ~= nil then
				QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange)
				RPredict = tP:GetPrediction(ts.target)
				if RReady then
				if RPredict then
					CastSpell(_R,RPredict.x,RPredict.z) 
				else
					CastSpell(_R,ts.target.x,ts.target.z) 
				end
				end
				if QReady then CastSpell(_Q, ts.target) end		
				if EReady then CastSpell(_E) end
				if WReady and myHero.mana > 100 then CastSpell(_W) end
				myHero:Attack(ts.target)
			end
		
		
		end
	--
		function MalzaharCombo()
			if not myHero.dead and ts.target ~= nil then
				if myHero.casting == 1 then return end
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
				EReady = (myHero:CanUseSpell(_E) == READY)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
				if EReady and not UltStarted then CastSpell(_E, ts.target) end
				if QReady and not UltStarted then CastSpell(_Q, ts.target.x,ts.target.z) end
				if WReady and not UltStarted then CastSpell(_W, ts.target.x,ts.target.z) end
				
				
				
				if RReady and getDmg("R",ts.target,myHero) > ts.target.health then CastSpell(_R,ts.target) end
				orbwalk()
		
			end
		end
		
		function MaokaiCombo()
			defaultCombo()
		end
		
		function MasterYiCombo()
			if not myHero.dead and ts.target ~= nil then
				if myHero.casting == 1 then return end
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY)
				RReady = (myHero:CanUseSpell(_R) == READY)
				if EReady then CastSpell(_E) end
				if QReady and (distanceToTarget > (myHero.range + GetDistance(myHero, myHero.minBBox)) or getDmg("Q",ts.target,myHero)) then CastSpell(_Q, ts.target) end
				if WReady and myHero.health < myHero.maxHealth*0.5 and ts.target.health > myHero.health then CastSpell(_W) end
				
				
				
				if RReady then CastSpell(_R) end
				orbwalk()
		
			end
		end
		
		function MissFortuneCombo()
			if not myHero.dead and ts.target ~= nil then
				if myHero.casting == 1 then return end
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY)
			
				if WReady then CastSpell(_W) end
				if EReady then CastSpell(_E, ts.target.x,ts.target.z) end
				if QReady then CastSpell(_Q,ts.target) end
				
				if RReady and getDmg("R",ts.target,myHero) * 10 > ts.target.health then CastSpell(_R,ts.target.x,ts.target.z) end
				orbwalk()
				
			end
		end
		
		function MordekaiserCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
				if QReady then CastSpell(_Q) end
				if WReady then CastSpell(_W, myHero) end
				if EReady then CastSpell(_E, ts.target.x,ts.target.z) end
				if RReady and getDmg("R",ts.target,myHero)>ts.target.health then CastSpell(_R,ts.target) end
				

				
				orbwalk()
			end
		end
	--	
		function MorganaCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
				if tpQ and QReady then 
                    local Q_P = tpQ:GetPrediction(ts.target)
                    if Q_P and not MinionCollision(myHero,D3DXVECTOR3(Q_P.x,0,Q_P.z), myHero:GetSpellData(_Q).lineWidth) then
                        CastSpell(_Q,Q_P.x,Q_P.z) 
                    end
                else
                    if QReady then CastSpell(_Q,ts.target.x,ts.target.z)  end
                end
               
				if WReady then CastSpell(_W, ts.target.x,ts.target.z) end
				if EReady then CastSpell(_E, myHero) end
				if RReady and getDmg("R",ts.target,myHero)+200>ts.target.health then CastSpell(_R) end
				

				
				orbwalk()
			end
		end
	--
	--N	
		function NamiCombo()
			defaultCombo()
		end
		
		function NasusCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
				if QReady then CastSpell(_Q) end
				if WReady then CastSpell(_W, ts.target) end
				if EReady then CastSpell(_E, ts.target.x,ts.target.z) end
				if RReady and myHero.health / myHero.maxHealth < 0.4  then CastSpell(_R) end
				

				
				myHero:Attack(ts.target)
			end
		end
	--	
		function NautilusCombo()
			defaultCombo()
		end
		
		function NidaleeCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				RReady = (myHero:CanUseSpell(_R) == READY)
				if myHero.range > 200 then
				--Ranged mode
					QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget < qRange)
					WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget < wRange)
					EReady = (myHero:CanUseSpell(_E) == READY)
					
                    
                    if tpQ and QReady then 
                        local Q_P = tpQ:GetPrediction(ts.target)
                        if Q_P and not MinionCollision(myHero,D3DXVECTOR3(Q_P.x,0,Q_P.z), myHero:GetSpellData(_Q).lineWidth) then
                            CastSpell(_Q,Q_P.x,Q_P.z) 
                        end
                    else
                        if QReady then CastSpell(_Q,ts.target.x,ts.target.z)  end
                    end
					
					if EReady and myHero.health < (myHero.maxHealth * 0.5) then CastSpell(_E, myHero) end -- Midpoint
					if WReady then CastSpell(_W,ts.target.x,ts.target.z) end
					if RReady and distanceToTarget < 700 then CastSpell(_R) end
				else
				--Melee mode
					QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget < qRange2)
					WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget < wRange2)
					EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget < eRange2)
					
					if QReady then CastSpell(_Q) end
					if EReady then CastSpell(_E) end
					if WReady then CastSpell(_W) end
					if RReady and not (QReady and EReady and WReady) then CastSpell(_R) end
				end
					
					orbwalk()
			end
		end
		
		function NocturneCombo()
			defaultCombo()
		end
		
		function NunuCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				if myHero.casting == 1 then
					if not castULT then castULT = GetTickCount() end
					if distanceToTarget > (7/8)*rRange or (((GetTickCount()-castULT)/3000)*getDmg("R",ts.target,myHero) > ts.target.health) then heroMove(ts.target.x,ts.target.z) end
					return
				end
				
				QReady = (myHero:CanUseSpell(_Q))
				WReady = (myHero:CanUseSpell(_W))
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange/2)
				if WReady then CastSpell(_W, myHero) end
				if EReady then CastSpell(_E, ts.target) end
				if RReady and getDmg("R",ts.target,myHero)>ts.target.health then CastSpell(_R) castULT = GetTickCount() end
				

				
				myHero:Attack(ts.target)
			end
		end
	--O	
		function OlafCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY)
				RReady = (myHero:CanUseSpell(_R) == READY)
				if RReady and myHero.health < ts.target.health then
					CastSpell(_R)
				end
				if QReady then CastSpell(_Q,ts.target.x,ts.target.z) end
				if WReady then CastSpell(_W) end
				if EReady then CastSpell(_E, ts.target) end
                orbwalk()
			end
		end
		
		function OriannaCombo()
			defaultCombo()
		end
	--P
		function PantheonCombo()
			if myHero.casting == 1 then return end
            local distanceToTarget = GetDistance(ts.target,myHero)
            local QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
            local WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
            local EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
            if QReady then 
                CastSpell(_Q,ts.target)
            end
            if WReady then CastSpell(_W,ts.target) end
            if EReady then CastSpell(_E,ts.target.x,ts.target.z) end
            if RReady and distanceToTarget > 1000 and getDmg("R",ts.target,myHero) > ts.target.health then CastSpell(_R,ts.target.x,ts.target.z) end
            orbwalk()
		end

		function PoppyCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
				if RReady and distanceToTarget <= eRange and EReady then
					CastSpell(_R, ts.target) CastSpell(_E, ts.target) invulnTime = GetTickCount() + 7000 
				end
				if QReady then CastSpell(_Q) end
				if WReady and (myHero.mana/myHero.maxMana > 0.5 or state == "retreat") then CastSpell(_W) end
				if EReady then CastSpell(_E, ts.target) end
			

				
				orbwalk()
			end
		end
	--Q
	--R
		function RammusCombo()
			casting(_Q)
			casting(_W)
			if EReady then CastSpell(_E, ts.target) end
			casting(_R)
			orbwalk()
		end
		
		function RenektonCombo()
			defaultCombo()
		end
		
		function RengarCombo()
            local distanceToTarget = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
            WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
            EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
            if QReady then CastSpell(_Q) end
            if WReady then CastSpell(_W) end
            if EReady then CastSpell(_E, ts.target) end
            if RReady then CastSpell(_R) end
            

            
            myHero:Attack(ts.target)
		end
		
		function RivenCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget <= wRange)
				EReady = (myHero:CanUseSpell(_E) == READY)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange - 50)
				if RReady and ts.target.health/ts.target.maxHealth < 0.5 then CastSpell(_R, ts.target.x,ts.target.z) end
				if QReady then CastSpell(_Q, ts.target.x,ts.target.z) end
				if WReady then CastSpell(_W) end
				if EReady then CastSpell(_E, ts.target.x,ts.target.z) end
			

				
				myHero:Attack(ts.target)
			end
		end
		
		function RumbleCombo()
			defaultCombo()
		end
		
		function RyzeCombo()
			--Ryze
			if not myHero.dead and myHero.cdr <= -0.35 and ts.target ~= nil then
				if myHero:CanUseSpell(_R) == READY then CastSpell(_R) end
				if myHero:CanUseSpell(_Q) == READY then
					   CastSpell(_Q,ts.target)
					   lastcast = _Q
				elseif (lastcast == _Q or lastcast == _W) and myHero:CanUseSpell(_W) == READY then
					   CastSpell(_W,ts.target)
					   lastcast = _W
				elseif (lastcast == _Q or lastcast == _E) and myHero:CanUseSpell(_E) == READY then
					   CastSpell(_E,ts.target)
					   lastcast = _E
				end	
			elseif (not myHero.dead and myHero.cdr > -0.35 and ts.target ~= nil) then
				if myHero:CanUseSpell(_R) == READY then CastSpell(_R) end
				if myHero:CanUseSpell(_Q) == READY then
					   CastSpell(_Q,ts.target)
					   lastcast = _Q
				elseif (lastcast == _Q or lastcast == _W) and myHero:CanUseSpell(_W) == READY and ts ~= nil then
					   CastSpell(_W,ts.target)
					   lastcast = _W
				elseif myHero:CanUseSpell(_E) == READY and myHero:CanUseSpell(_W) ~= READY and myHero:CanUseSpell(_Q) ~= READY and ts ~= nil then
					   CastSpell(_E,ts.target)
					   lastcast = _E
				end	
			end
			
                orbwalk()
			
		end
	--S	
		function SejuaniCombo()
			defaultCombo()
		end
		
		function ShacoCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				
				QReady = (myHero:CanUseSpell(_Q) == READY)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget > 500 or getDmg("E",ts.target,myHero))
				RReady = (myHero:CanUseSpell(_R) == READY)
				
				if QReady then CastSpell(_Q,ts.target.x,ts.target.z) end
				if WReady then CastSpell(_W,myHero.x,myHero.z) end
				if EReady and ePred then CastSpell(_E, ts.target) end
				if RReady and not clone then CastSpell(_R) 
				elseif clone and GetDistance(clone, ts.target) > 350 then
					CastSpell(_R,ts.target)				
				end

				
				orbwalk()
			end
		end
		
		function ShenCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				local ePred = tpE:GetPrediction(ts.target)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and distanceToTarget < 700)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				
				
				if QReady then CastSpell(_Q,ts.target) end
				if WReady then CastSpell(_W) end
				if EReady and ePred then CastSpell(_E, ePred.x,ePred.z) end
			

				
				orbwalk()
			end
		end
		
		function ShyvanaCombo()
			defaultCombo()
		end
		
		function SingedCombo()
			defaultCombo()
		end

		function SionCombo()
			if not myHero.dead and ts.target ~= nil then
				QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
				EReady = (myHero:CanUseSpell(_E) == READY)
				RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange)
				if EReady and not sionECasted then
				CastSpell(_E)
				sionECasted = true	
				end
				if WReady then CastSpell(_W) end
				if QReady then CastSpell(_Q, ts.target) end
				if RReady then CastSpell(_R) end
				myHero:Attack(ts.target)
			end
		end
	--	
		function SivirCombo()
            WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
            EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
            RReady = (myHero:CanUseSpell(_R) == READY)
            if (myHero:CanUseSpell(_Q) == READY) then 
                local Q_P = tpQ:GetPrediction(ts.target)
                if Q_P then CastSpell(_Q,Q_P.x,Q_P.z) end
            end
            if (myHero:CanUseSpell(_W) == READY) then CastSpell(_W) end
            if EReady then CastSpell(_E) end		
            if RReady and myHero.health > ts.target.health then CastSpell(_R) end			
            orbwalk()
		end
	--	
		function SkarnerCombo()
            local distanceToTarget = GetDistance(ts.target,myHero)
			if (myHero:CanUseSpell(_W) == READY) then
                CastSpell(_W)
            end
            if (myHero:CanUseSpell(_Q) == READY) and distanceToTarget <= qRange then 
                CastSpell(_Q)
            end
            if (myHero:CanUseSpell(_E) == READY) and distanceToTarget <= eRange then
                CastSpell(_E,ts.target.x,ts.target.z)
            end
            if (myHero:CanUseSpell(_R) == READY) then
                CastSpell(_R,ts.target)
            end
            orbwalk()
		end
		
		function SonaCombo()
            if (myHero:CanUseSpell(_W) == READY) then
                CastSpell(_W)
            end
            if (myHero:CanUseSpell(_Q) == READY) then 
                CastSpell(_Q)
            end
            if (myHero:CanUseSpell(_E) == READY) then
                CastSpell(_E)
            end
            if (myHero:CanUseSpell(_R) == READY) then
                local R_P = tpR:GetPrediction(ts.target)
                if R_P then CastSpell(_R,R_P.x,R_P.z) end
            end
            orbwalk()
		end
		
		function SorakaCombo()
			defaultCombo()
		end
		
		function SwainCombo()
			defaultCombo()
		end
		
		function SyndraCombo()
			defaultCombo()
		end
	--T	
		function TalonCombo()
			defaultCombo()
		end
		
		function TaricCombo()
			defaultCombo()
		end
		
		function TeemoCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY)
				
				RReady = (myHero:CanUseSpell(_R) == READY)
				
				if QReady then CastSpell(_Q,ts.target) end
				if WReady and GetDistance(ts.target) > myHero.range then CastSpell(_W) end
				
				if not QReady and RReady and distanceToTarget < rRange then CastSpell(_R, ( ts.target.x + myHero.x )/ 2,(myHero.z + ts.target.z)/2) return end
				
				
				orbwalk()
			end
		end

		function ThreshCombo()
			defaultCombo()		
		end
		
		function TristanaCombo()
            local useW = false
            local distanceToTarget = GetDistance(ts.target,myHero)
            local WReady = (myHero:CanUseSpell(_W) == READY)
            local RReady = (myHero:CanUseSpell(_R) == READY)
            if (myHero:CanUseSpell(_Q) == READY) and distanceToTarget < myHero.range then
                CastSpell(_Q) 
            end
            if RReady then
                if not (myHero.health > 200 and getDmg("AD",ts.target,myHero) * 2 > ts.target.health) then
                    if getDmg("R",ts.target,myHero) > ts.target.health then CastSpell(_R,ts.target) end
                end
            end
            if WReady then 
                if distanceToTarget < wRange then
                    if getDmg("W",ts.target,myHero) > ts.target.health or (RReady and myHero.mana >= 180 and getDmg("W",ts.target,myHero) + getDmg("R",ts.target,myHero) > ts.target.health) then
                        useW = true
                    end
                else
                    if RReady and myHero.mana >= 180 and getDmg("R",ts.target,myHero) > ts.target.health then
                        useW = true
                    end
                end
                if useW then 
                    local W_P = tpW:GetPrediction(ts.target)
                    if W_P then CastSpell(_W,W_P.x,W_P.z) end
                end
            end
            if (myHero:CanUseSpell(_E) == READY) then
                CastSpell(_E,ts.target) 
            end
            orbwalk()   
        end
		
		function TrundleCombo()
            QReady = (myHero:CanUseSpell(_Q) == READY)
            WReady = (myHero:CanUseSpell(_W) == READY)
            EReady = (myHero:CanUseSpell(_E) == READY)
            RReady = (myHero:CanUseSpell(_R) == READY)
            if QReady then CastSpell(_Q) end
            if WReady then CastSpell(_W,ts.target.x,ts.target.z) end
            if EReady then CastSpell(_E,makeDistance(myHero,ts.target,-150)) end
            if RReady then CastSpell(_R,ts.target) end
            orbwalk()
		end
		
		function TryndamereCombo()
			myHero:Attack(ts.target)
			QReady = (myHero:CanUseSpell(_Q) == READY)
			WReady = (myHero:CanUseSpell(_W) == READY)
			EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
			RReady = (myHero:CanUseSpell(_R) == READY)
			if myHero.mana == 100 and QReady then CastSpell(_Q) end
			if WReady then CastSpell(_W,ts.target.x,ts.target.z) end
			if EReady then CastSpell(_E,makeDistance(ts.target,myHero,500)) end
			if RReady and myHero.health < 100 then CastSpell(_R) end
		end
		
		function TwistedFateCombo()
            local distanceToTarget = GetDistance(ts.target,myHero)
			QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget < qRange)
            WReady = (myHero:CanUseSpell(_W) == READY)
            EReady = (myHero:CanUseSpell(_E) == READY)
            
            
            if tpQ and QReady then 
                local Q_P = tpQ:GetPrediction(ts.target)
                if Q_P and not MinionCollision(myHero,D3DXVECTOR3(Q_P.x,0,Q_P.z), myHero:GetSpellData(_Q).lineWidth) then
                    CastSpell(_Q,Q_P.x,Q_P.z) 
                end
            else
                if QReady then CastSpell(_Q,ts.target.x,ts.target.z)  end
            end
            if WReady then CastSpell(_W) end
            orbwalk()
		end
		
		function TwitchCombo()
			defaultCombo()
		end
	--U	
		function UdyrCombo()
			defaultCombo()
		end
		
		function UrgotCombo()
			defaultCombo()
		end
	--V	
		function VarusCombo()
			if not myHero.dead and ts.target ~= nil then
				QReady = (myHero:CanUseSpell(_Q) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
				RReady = (myHero:CanUseSpell(_R) == READY)
				
				if EReady then CastSpell(_E,ts.target.x,ts.target.z) end
				if RReady then CastSpell(_R,ts.target.x,ts.target.z) end
				
				if QReady and not casted then CastSpell(_Q,ts.target.x,ts.target.z) casted = true castTime = GetTickCount() return end
				if casted and castTime + 1000 > GetTickCount() then
					
					casted = false
					p = CLoLPacket(varusQ)
					p:EncodeF(myHero.networkID)
					p:Encode1(128) --Q
					p:EncodeF(ts.target.x)
					p:EncodeF(ts.target.y)
					p:EncodeF(ts.target.z)
					p.dwArg1 = 1
					p.dwArg2 = 0
					SendPacket(p)
	   
				
				end
				
				orbwalk()
			end
		end
		
		function VayneCombo()
            QReady = (myHero:CanUseSpell(_Q) == READY)
            EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and (GetDistance(ts.target,myHero) > myHero.range or ts.target.health/ts.target.maxHealth < 0.5))
            
            if EReady then CastSpell(_E,ts.target) end
            if RReady then CastSpell(_R) end
            if QReady and castAttack and GetTickCount() > castAttack then 
                if myHero.health/myHero.maxHealth > ts.target.health/ts.target.maxHealth and GetDistance(ts.target,myHero) > 400 then
                CastSpell(_Q,makeDistance(ts.target,myHero,myHero.range/2))
                elseif GetDistance(ts.target,myHero) < (1/2)*(myHero.range+150) and ts.target.health > 200 then
                CastSpell(_Q,makeDistance(myHero,ts.target,700))
                end
            end		
            orbwalk()
		end
		--
		function VeigarCombo()
			defaultCombo()
		end
		--
        function VelkozCombo()
			QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
			WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
			EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
			RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange)
            
            if QReady and (velkozQDelay or 0) < os.clock() then 
                CastSpell(_Q,ts.target.x,ts.target.z)
                velkozQDelay = os.clock() +3
            end
            if WReady then 
                CastSpell(_W,ts.target.x,ts.target.z)
            end
            if EReady then 
                CastSpell(_E,ts.target.x,ts.target.z)
            end
            if RReady and (velkozRDelay or 0) < os.clock() then 
                CastSpell(_R,ts.target.x,ts.target.z)
                velkozRDelay = os.clock() + 2.5
            end
            
		end
        
		function ViCombo()
			local  tQ = TargetPrediction(1150, 1, 0, 700, 50)
			QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
			WReady = (myHero:CanUseSpell(_W) == READY)
			EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
			RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange)
			if os.clock() > lastBasicAttack + 0.5 then
				swing = 0
			end

			--Combo's and such
			if ts.target ~= nil then
				myHero:Attack(ts.target)   
				QPredict = tQ:GetPrediction(ts.target)
				if QPredict ~= nil then
				   
					--ItemSupport
					UseItems(ts.target)
					--Vi's R
					if RReady and getDmg("R",ts.target,myHero) + 300 > ts.target.health then CastSpell(_R, ts.target) end
					
				   
					--Vi's Q
					if QReady then
					targetX, targetZ = QPredict.x,QPredict.z
						CastSpellQ()
						
					end
					   
					myHero:Attack(ts.target)       
					--Auto E after auto attack
					if EReady and QReady == false and os.clock() - lastBasicAttack > swingDelay and GetDistance(ts.target) <= EMinRange + 125 then
						CastSpell(_E)
					end
				end
			end
		end
	--	
		function ViktorCombo()
			defaultCombo()
		end
		
		function VladimirCombo()
			defaultCombo()
		end
		
		function VolibearCombo()
            local dist = GetDistance(ts.target,myHero)
            QReady = (myHero:CanUseSpell(_Q) == READY)
			WReady = (myHero:CanUseSpell(_W) == READY and dist < wRange)
            EReady = (myHero:CanUseSpell(_E) == READY and dist < eRange)
            RReady = (myHero:CanUseSpell(_R) == READY and dist < myAttackRange())
            
            if QReady then CastSpell(_Q) end
            if WReady and getDmg("W",ts.target,myHero) > ts.target.health then CastSpell(_W,ts.target) end
            if EReady then CastSpell(_E) end
            if RReady then CastSpell(_R) end
            orbwalk()
		end
	--W	
		function WarwickCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)

				if EReady and not warwickE then
				CastSpell(_E)
				warwickE = true
				end
				if QReady then CastSpell(_Q,ts.target) end
				if WReady and (myHero.mana > 100 or myHero.health < ts.target.health) then CastSpell(_W) end
				
				if RReady and not QReady and getDmg("R",ts.target,myHero) then CastSpell(_R,ts.target) end
				
				
				myHero:Attack(ts.target)
			end
		end
		
		function MonkeyKingCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)

				if EReady then CastSpell(_E, ts.target) end
				if QReady then CastSpell(_Q) end
				if WReady and (myHero.mana > 100 or myHero.health < ts.target.health) then CastSpell(_W) end
				
				if RReady and getDmg("R",ts.target,myHero) * 3 then CastSpell(_R) end
				
				
				myHero:Attack(ts.target)
			end
		end
	--X	
		function XerathCombo()
			defaultCombo()
		end
		
		function XinZhaoCombo()
			if not myHero.dead and ts.target ~= nil then
				QReady = (myHero:CanUseSpell(_Q) == READY and GetDistance(ts.target,myHero) < qRange)
				WReady = (myHero:CanUseSpell(_W) == READY and GetDistance(ts.target,myHero) < wRange)
				EReady = (myHero:CanUseSpell(_E) == READY and GetDistance(ts.target,myHero) < eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and GetDistance(ts.target,myHero) < rRange)
				if EReady then CastSpell(_E, ts.target) end
				if WReady then CastSpell(_W) end
				if QReady then CastSpell(_Q) end
				if RReady then CastSpell(_R) end
				myHero:Attack(ts.target)
			end
		end
	--
	--Y
		function YorickCombo()
			defaultCombo()
		end

	--Z	
		function ZedCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
				if RReady and getDmg("R",ts.target,myHero) then CastSpell(_R, ts.target) end
				if WReady then CastSpell(_W, ts.target.x,ts.target.z) end
				if QReady then CastSpell(_Q, ts.target.x,ts.target.z) end
				if EReady then CastSpell(_E) end
				

				
				myHero:Attack(ts.target)
			end
		end
		
		function ZiggsCombo()
			defaultCombo()
		end
		
		function ZileanCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
				if QReady then CastSpell(_Q, ts.target) end
				if WReady and not QReady and not EReady then CastSpell(_W) end
				if EReady then CastSpell(_E, myHero) end
				if RReady and myHero.health / myHero.maxHealth < 0.45  then CastSpell(_R) end
				

				
				myHero:Attack(ts.target)
			end
		end
		
		function ZyraCombo()
			if not myHero.dead and ts.target ~= nil then
				local distanceToTarget = GetDistance(ts.target,myHero)
				QReady = (myHero:CanUseSpell(_Q) == READY and distanceToTarget <= qRange)
				WReady = (myHero:CanUseSpell(_W) == READY)
				EReady = (myHero:CanUseSpell(_E) == READY and distanceToTarget <= eRange)
				RReady = (myHero:CanUseSpell(_R) == READY and distanceToTarget <= rRange)
				if QReady then CastSpell(_Q, ts.target) end
				if WReady and not QReady and not EReady then CastSpell(_W) end
				if EReady then CastSpell(_E, myHero) end
				if RReady and myHero.health / myHero.maxHealth < 0.45  then CastSpell(_R) end
				

				
				myHero:Attack(ts.target)
			end
		end
		
		function casting(spell)
			if myHero:CanUseSpell(spell) == READY then
				--As if skillshot first
				CastSpell(spell,ts.target.x,ts.target.z)
				--As if targetted spell second
				CastSpell(spell,ts.target)
				--As if its an activation spell
				CastSpell(spell)
				--And if its a buff
				--CastSpell(spell,myHero)
			end
		end
		
		function defaultCombo()
			casting(_Q)
			casting(_W)
			casting(_E)
			casting(_R)
			orbwalk()
		
		end
		
	end	

	--[[ AUTO REPLY ]]--
	function OnRecvChat(from,msg)
	--output(string.format("%-16s : %s",tostring(from),tostring(msg)))
	--	if string.find(msg,"hi") then SendChat("hello") end
        print(msg)
	end
	
	function getReplyList()
	
	
	end
	
	
	--[[ CHAMP LOADOUT ]]--
	function LoadChampionDetails() 
		--DEFAULT ITEM SETS (for ease...)
		local AD_CARRY = {"Berserker's Greaves", "Sanguine Blade","Trinity Force","Frozen Mallet","Last Whisper","Runaan's Hurricane"}
		local TANK_AP = {"Ninja Tabi","Sunfire Cape","Abyssal Scepter","Frozen Heart","Blackfire Torch","Randuin's Omen"}
		local TANK_AD = {"Mercury's Treads","Trinity Force","Randuin's Omen","Maw of Malmortius","Sunfire Cape","Guardian Angel"}
		local APmana = {}
		local APBurst = {"Sorcerer's Shoes","Haunting Guise","Blackfire Torch","Wooglet's Witchcap","Void Staff","Odyn's Veil"}
		local AD_BRUISER = {"Berserker's Greaves","Ravenous Hydra","Sanguine Blade","Guardian Angel","Randuin's Omen","Zephyr"} 
		local APMeleeBurst = {"Sorcerer's Shoes","Haunting Guise","Rylai's Crystal Scepter","Lich Bane","Void Staff","Odyn's Veil"}
		local APBurstNoMana = {"Sorcerer's Shoes","Wooglet's Witchcap","Rylai's Crystal Scepter","Void Staff","Hextech Gunblade","Abyssal Scepter"}
		local FormChangers = {"Mercury's Treads","Trinity Force","Sanguine Blade","Manamune","Frozen Mallet","Randuin's Omen"}
        local Hybrids = {"Mercury's Treads","Trinity Force","Hextech Gunblade","Guinsoo's Rageblade","Guardian Angel","Randuin's Omen"}
		champions = {
			{--AATROX done
			name = "Aatrox",
			skillRanges = {880,800,975,450},
            farmSpells = {{_E,5}},
			levelSequence = getSkillOrder("WEQ"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "AatroxCombo"
			},
			{--AHRI done
			name = "Ahri",
			skillRanges = {880,800,975,450},
            farmSpells = {{_Q,5}},
            tpQ = {880, 1700, 0.25},
			tpE = {975, 1600, 0.1, 90},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "AhriCombo"
			},	
			{--AKALI done
			name = "Akali",
			stealth = {_W,2},
            farmSpells = {{_Q,4}},
			skillRanges = {600,700,325,800},
			levelSequence = getSkillOrder("QEW"),
			items = {"Sorcerer's Shoes","Hextech Gunblade","Trinity Force","Wooglet's Witchcap","Maw of Malmortius","Rylai's Crystal Scepter"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "AkaliCombo",
			extra = "AkaliObject"
			},	
			{--ALISTAR done
			name = "Alistar",
			skillRanges = {365,650,575,350},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "AlistarCombo"
			},	
			{--AMUMU done
			name = "Amumu",
			skillRanges = {1100,400,300,600},
			levelSequence = getSkillOrder("EQW"),
			items = TANK_AP,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "AmumuCombo",
			extra = "AmumuObject"
			},
			{--ANIVIA done
			name = "Anivia",
			skillRanges = {1100,1000,650,625},
			levelSequence = getSkillOrder("EQW"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "AniviaCombo",
			extra = "AniviaObject"
			},
			{--ANNIE done
			name = "Annie",
            farmSpells = {{_Q,4},{_W,5,600}},
			skillRanges = {625,600,100,600},
			levelSequence = getSkillOrder("WQE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "AnnieCombo",
			extra = "AnnieObject"
			},				
			{--ASHE done
			name = "Ashe",
            farmSpells = {{_W,5}},
			skillRanges = {0,1200,5500,10000},
			levelSequence = getSkillOrder("WQE"),
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "AsheCombo",
			extra = "AsheObject"
			},		
			{--BLITZCRANK done
			name = "Blitzcrank",
			skillRanges = {925,400,400,600},
			levelSequence = getSkillOrder("WEQ"),
			items = Hybrids,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "BlitzcrankCombo"
			},	
			{--BRAND done
			name = "Brand",
			skillRanges = {900,900,625,750},
			levelSequence = getSkillOrder("WQE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "BrandCombo"
			},
			{--CAITLYN done
			name = "Caitlyn",
            farmSpells = {{_Q,5}},
			skillRanges = {1300,800,1000,3000},
			levelSequence = getSkillOrder("QEW"),
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "CaitlynCombo",
			extra = "CaitlynVariables"
			},
			{--CASSIOPEIA done
			name = "Cassiopeia",
            tpQ = {925, math.huge, 0.6},
            tpW = {925, math.huge, 0.375},
            tpR = {750, math.huge, 0.5},
			skillRanges = {850,850,700,850},
			levelSequence = getSkillOrder("QEW"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "CassiopeiaCombo",
			extra = "CassiopeiaVariables"
			},
			{--CHO'GATH done
			name = "Chogath",
            tpQ = {1125, math.huge, 0.625},
			skillRanges = {1125,650,650,500},
			levelSequence = getSkillOrder("WQE"),
			tpVIP = {830, 2000, 0.8, 450},
			items = {"Mercury's Treads","Rod of Ages","Frozen Heart", "Thornmail", "Sunfire Cape", "Randuin's Omen"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "ChogathCombo",
			extra = "ChogathVariables"
			},
			{--CORKI done
			name = "Corki",
            farmSpells = {{_Q,5},{_R,5}},
			skillRanges = {700,800,600,1225},
			levelSequence = getSkillOrder("QEW"),
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "CorkiCombo",
			extra = "CorkiVariables"
			},			
			{--DARIUS done
			name = "Darius",
            farmSpells = {{_Q,1}},
			skillRanges = {425,250,550,475},
			levelSequence = getSkillOrder("QWE"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "DariusCombo",
			extra = "DariusObject"
			},
			{--DIANA done
			name = "Diana",
            farmSpells = {{_Q,5},{_W,1}},
			skillRanges = {830,200,300,825},
			levelSequence = getSkillOrder("QWE"),
			tpVIP = {830, 1800, 0.25, 10},
			items = APMeleeBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "DianaCombo",
			extra = "DianaObject"
			},	
			{--DRMUNDO done
			name = "DrMundo",
            farmSpells = {{_Q,5}},
			skillRanges = {1000,325,325,100},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "DrMundoCombo"
			},	
			{--DRAVEN done
			name = "Draven",
            tpE = {950, 1370, 0.3,130},
            farmSpells = {{_E,5}},
			skillRanges = {550,325,925,100000},
			levelSequence = getSkillOrder("QEW"),
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "DravenCombo",
			extra = "DravenObject"
			},
			{--ELISE done
			name = "Elise",
			skillRanges = {650,950,1075,500}, --transforms
			levelSequence = getSkillOrder("QEW"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "EliseCombo"
			},
			{--EVELYNN done
			name = "Evelynn",
			skillRanges = {500,300,225,650},
			levelSequence = getSkillOrder("QEW"),
			items = {"Sorcerer's Shoes","Rylai's Crystal Scepter","Lich Bane","Wooglet's Witchcap","Void Staff","Abyssal Scepter"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "EvelynnCombo"
			},
			{--EZREAL done
			name = "Ezreal",
            farmSpells = {{_Q,5}},
            tpQ = {1150, 2000, 0.250, 80},
            tpW = {1050, 1600, 0.250, 80},
            tpR = {2000, 1700, 1, 80},
			skillRanges = {1100,900,750,math.huge}, 
			levelSequence = getSkillOrder("QWE"),
			items = {"Berserker's Greaves","Manamune", "Sanguine Blade","Iceborn Gauntlet","Last Whisper","Runaan's Hurricane"},
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "EzrealCombo",
			extra = "EzrealObject",
			},			
			{--FIDDLESTICKS done
			name = "FiddleSticks",
            farmSpells = {{_E,4}},
			skillRanges = {575,475,750,800},
			levelSequence = getSkillOrder("QWE"),
			items =  {"Sorcerer's Shoes","Haunting Guise","Abyssal Scepter","Wooglet's Witchcap","Void Staff","Odyn's Veil"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "FiddleSticksCombo",
			extra = "FiddleSticksObject"
			},	
			{--FIORA done
			name = "Fiora",
            farmSpells = {{_Q,4},{_E,1}},
			skillRanges = {600,300,300,400},
			levelSequence =  getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "FioraCombo"
			},	
			{--FIZZ
			name = "Fizz",
            farmSpells = {{_Q,4},{_E,5}},
			skillRanges = {650,625,649,0},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "FiddleSticksCombo"
			},	
			{--GALIO
			name = "Galio",
            farmSpells = {{_Q,5},{_W,1},{_E,5}},
			skillRanges = {650,625,649,0},
			levelSequence = getSkillOrder("QWE"),
			items = TANK_AP,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "GalioCombo"
			},	
			{--GANGPLANK
			name = "Gangplank",
            farmSpells = {{_Q,4}},
			skillRanges = {650,625,649,0},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "GangplankCombo"
			},	
			{--GAREN done
			name = "Garen",
            farmSpells = {{_Q,1},{_E,1}},
			skillRanges = {200,0,330,400},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "GarenCombo"
			},
			{--GRAGAS
			name = "Gragas",
            farmSpells = {{_Q,5},{_E,5}},
			skillRanges = {650,625,649,0},
			levelSequence = getSkillOrder("QEW"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "GragasCombo"
			},	
			{--GRAVES -item
			name = "Graves",
            farmSpells = {{_Q,5},{_E,5}},
			skillRanges = {650,625,649,0},
			levelSequence = getSkillOrder("QEW"),
			items =	AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "GravesCombo"
			},	
			{--HECARIM
			name = "Hecarim",
            farmSpells = {{_Q,1},{_W,1}},
			skillRanges = {650,625,649,0},
			levelSequence = getSkillOrder("QWE"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "HecarimCombo"
			},	
			{--HEIMERDINGER
			name = "Heimerdinger",
            farmSpells = {{_W,1},{_E,5}},
			skillRanges = {250,1000,925,0},
			levelSequence = getSkillOrder("QWE"),
			items = {"Boots of Mobility","Wooglet's Witchcap","Athene's Unholy Grail","Guardian Angel","Archangel's Staff","Randuin's Omen"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "HeimerdingerCombo",
			extra = "HeimerdingerObject"
			},	
			{--IRELIA
			name = "Irelia",
			skillRanges = {650,300,425,1000},
			levelSequence = getSkillOrder("WEQ"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "IreliaCombo"
			},	
			{--JANNA
			name = "Janna",
            farmSpells = {{_Q,5},{_W,4},{_E,3}},
			skillRanges = {650,625,649,0},
			levelSequence = getSkillOrder("EWQ"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "JannaCombo"
			},	
			{--JARVAN --done
			name = "JarvanIV",
            farmSpells = {{_Q,5},{_W,1},{_E,5}},
			skillRanges = {770,300,830,650},
			levelSequence = getSkillOrder("QWE"),
			items = TANK_AD,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "JarvanIVCombo"
			},	
			{--JAX done
			name = "Jax",
            farmSpells = {{_Q,4},{_W,1},{_E,1}},
			skillRanges = {700,250,900,600},
			levelSequence = getSkillOrder("WQE"),
			items = Hybrids,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "JaxCombo"
			},	
			{--JAYCE
			name = "Jayce",
            skillRanges = {600,285,240,0,1050,0,650,0},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "JayceCombo"
			},	
            {--JINX
			name = "Jinx",
            skillRanges = {0,1500,900,20000},
			levelSequence = getSkillOrder("QWE"),
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "JinxCombo"
			},
            {--KARMA
			name = "Karma",
            farmSpells = {{_Q,5},{_E,3}},
			skillRanges = {950,650,800,0},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "KarmaCombo"
			},	
			{--KARTHUS
			name = "Karthus",
            farmSpells = {{_Q,5},{_E,1}},
			skillRanges = {875,1000,425,0},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "KarthusCombo"
			},	
			{--KASSADIN
			name = "Kassadin",
            farmSpells = {{_E,5}},
			skillRanges = {650,0,400,700},
			levelSequence = getSkillOrder("QEW"),
			items = {"Sorcerer's Shoes","Rod of Ages","Rylai's Crystal Scepter","Wooglet's Witchcap","Void Staff","Abyssal Scepter"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "KassadinCombo"
			},	
			{--KATARINA
			name = "Katarina",
            farmSpells = {{_Q,4},{_W,1},{_E,4}},
			skillRanges = {675,375,700,550},
			levelSequence = getSkillOrder("QWE"),
			items = APBurstNoMana,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "KatarinaCombo",
			extra = "KatarinaObject"
			},
			{--KAYLE
			name = "Kayle",
            farmSpells = {{_E,1}},
			skillRanges = {650,900,525,900},
			levelSequence = getSkillOrder("QEW"),
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "KayleCombo"
			},
			{--KENNEN
			name = "Kennen",
            farmSpells = {{_Q,5},{_W,1}},
			skillRanges = {1050,800,200,550},
			levelSequence = getSkillOrder("WQE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "KennenCombo"
			},
			{--KHA'ZIX
			name = "Khazix",
            farmSpells = {{_Q,4},{_W,5}},
			skillRanges = {325,1000,600,0},
			levelSequence = getSkillOrder("QWE"),
			tpVIP = {1150, 1175, 0.250, 80},
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "KhazixCombo"
			},
			{--KOGMAW done
			name = "KogMaw",
            farmSpells = {{_E,5}},
			skillRanges = {652,0,1000,1400},
			levelSequence = getSkillOrder("WQE"),
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "KogMawCombo",
			extra = "KogMawVariables"
			},		
			{--LEBLANC
			name = "Leblanc",
            farmSpells = {{_W,5}},
			skillRanges ={700,600,950,700},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "LeblancCombo",
			extra = "LeblancVariables"
			},		
			{--LEESIN
			name = "LeeSin",
            farmSpells = {{_E,1}},
			skillRanges = {975,700,450,375},
			levelSequence = getSkillOrder("EQW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "LeeSinCombo",
			extra = "LeeSinVariables"
			},					
			{--LEONA done
			name = "Leona",
            tpE = {900,2000,0.25},
            tpR = {1200,1000,0.25},
            farmSpells = {{_W,1},{_E,5}},
			skillRanges = {0,0,700,1200},
			levelSequence = getSkillOrder("WEQ"),
			items = TANK_AP,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "LeonaCombo"
			},	
			{--Lissandra done
			name = "Lissandra",
            farmSpells = {{_Q,5}},
			skillRanges = {0,0,700,1200},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "LissandraCombo"
			},	
			{--Lucian
			name = "Lucian",
            farmSpells = {{_Q,5},{_W,5}},
			skillRanges = {550,1000,425,1400},
			levelSequence = getSkillOrder("QWE"),
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "LucianCombo"
			},	
			{--LULU
			name = "Lulu",
            farmSpells = {{_E,4},{_Q,5}},
			skillRanges = {925,650,650,900},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "LuluCombo"
			},	
			{--LUX
			name = "Lux",
            farmSpells = {{_E,5}},
			skillRanges = {1175,1075,1100,3000},
			levelSequence = getSkillOrder("QEW"),
			tpR = {3000, math.huge, 0.700, 200},
			items = {"Sorcerer's Shoes","Haunting Guise","Morellonomicon","Wooglet's Witchcap","Void Staff","Odyn's Veil"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "LuxCombo"
			},	
			{--MALPHITE --itemdone
			name = "Malphite",
            farmSpells = {{_E,1}},
			skillRanges = {625,625,400,1000},
			levelSequence = getSkillOrder("QEW"),
			items = TANK_AP,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "MalphiteCombo"
			},	
			{--MALZAHAR
			name = "Malzahar",
            farmSpells = {{_E,4},{_Q,5}},
			skillRanges = {900,800,650,700},
			levelSequence = getSkillOrder("QEW"),
			items = {"Sorcerer's Shoes","Will of the Ancients","Rod of Ages","Rylai's Crystal Scepter","Wooglet's Witchcap","Blackfire Torch"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "MalzaharCombo",
			extra = "MalzaharObject"
			},
			{--MAOKAI
			name = "Maokai",
            farmSpells = {{_E,5},{_Q,5},{_R,5}},
			skillRanges = {600,650,1100,625},
			levelSequence = getSkillOrder("QEW"),
			items = {"Mercury's Treads","Rod of Ages","Blackfire Torch","Frozen Heart","Abyssal Scepter","Wooglet's Witchcap"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "MaokaiCombo",
			extra = "MaokaiObject"
			},
			{--MASTER YI --itemdone
			name = "MasterYi",
            farmSpells = {{_Q,4}},
			skillRanges = {600,0,400,0},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "MasterYiCombo"
			},
			{--MISSFORTUNE
			name = "MissFortune",
            farmSpells = {{_E,5},{_W,1}},
			skillRanges = {550,0,800,1400},
			levelSequence = getSkillOrder("WQE"),
			items = {"Berserker's Greaves","Sanguine Blade","Trinity Force","Phantom Dancer","Frozen Mallet","Guardian Angel"},
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "MissFortuneCombo",
			extra = "MissFortuneObject"
			},
			{--MORDEKAISER
			name = "Mordekaiser",
            farmSpells = {{_E,5},{_Q,1},{_W,3}},
			skillRanges = {600,750,700,850},
			levelSequence = getSkillOrder("EQW"),
			items = {"Sorcerer's Shoes","Rylai's Crystal Scepter","Will of the Ancients","Blackfire Torch","Wooglet's Witchcap","Frozen Heart"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "MordekaiserCombo",
			extra = "MordekaiserObject"
			},
			{--MORGANA
			name = "Morgana",
            farmSpells = {{_W,5}},
			skillRanges = {1300,900,750,600},
			levelSequence = getSkillOrder("EQW"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "MorganaCombo",
			extra = "MorganaObject"
			},
			{--NAMI
			name = "Nami",
            farmSpells = {{_Q,5},{_E,1}},
			skillRanges = {875,725,800,2750},
			levelSequence = getSkillOrder("EQW"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "NamiCombo",
			extra = "NamiObject"
			},
			{--NASUS done
			name = "Nasus",
            farmSpells = {{_Q,1},{_E,5}},
			skillRanges = {350,700,650,0},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "NasusCombo"
			},			
			{--NAUTILUS
			name = "Nautilus",
            farmSpells = {{_E,1},{_W,1}},
			skillRanges = {950,0,600,850},
			levelSequence = getSkillOrder("WQE"),
			items = TANK_AP,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "NautilusCombo"
			},			
			{--NIDALEE
			name = "Nidalee",
			skillRanges = {1500,900,600,0,350,375,300,0},
			levelSequence = getSkillOrder("QEW"),
			items = FormChangers,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "NidaleeCombo"
			},			
			{--NOCTURNE
			name = "Nocturne",
            farmSpells = {{_Q,5}},
			skillRanges = {1200,350,425,0},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "NocturneCombo"
			},			
			{--NUNU
			name = "Nunu",
            farmSpells = {{_Q,4},{_W,3},{_E,4}},
			skillRanges = {150,700,550,1300},
			levelSequence = getSkillOrder("EWQ"),
			items =  {"Sorcerer's Shoes","Rod of Ages","Frozen Heart","Abyssal Scepter","Will of the Ancients","Odyn's Veil"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "NunuCombo"
			},			
			{--OLAF
			name = "Olaf",
            farmSpells = {{_Q,5},{_W,1}},
			skillRanges = {1000,325,325,0},
			levelSequence = getSkillOrder("EWQ"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "OlafCombo"
			},			
			{--ORIANNA
			name = "Orianna",
            farmSpells = {{_Q,5},{_E,3}},
			skillRanges = {825,250,1100,350},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "OriannaCombo"
			},			
			{--PANTHEON
			name = "Pantheon",
            farmSpells = {{_E,5},{_Q,4}},
			skillRanges = {600,600,600,5500},
			levelSequence = getSkillOrder("EQW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "PantheonCombo"
			},
			{--POPPY
			name = "Poppy",
            farmSpells = {{_W,1},{_Q,1}},
			skillRanges = {350,0,525,900},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "PoppyCombo"
			},
			{--QUINN
			name = "Quinn",
            farmSpells = {{_Q,5}},
			skillRanges = {1025,2100,750,0,1025,2100,750,0},
			levelSequence = getSkillOrder("QWE"),
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "QuinnCombo"
			},
			{--RAMMUS
			name = "Rammus",
            farmSpells = {{_Q,1},{_R,1},{_W,1}},
			skillRanges = {0,0,325,300},
			levelSequence = getSkillOrder("QEW"),
			items = TANK_AP,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "RammusCombo"
			},
			{--RENEKTON
			name = "Renekton",
            farmSpells = {{_E,5},{_Q,1},{_W,4}},
			skillRanges = {225,0,450,175},
			levelSequence = getSkillOrder("QWE"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "RenektonCombo"
			},
			{--RENGAR
			name = "Rengar",
            farmSpells = {{_E,4},{_Q,1},{_W,1}},
			skillRanges = {350,500,575,900},
			levelSequence = getSkillOrder("EQW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "RengarCombo"
			},
			{--RIVEN
			name = "Riven",
            farmSpells = {{_Q,5},{_E,5}},
			skillRanges = {372.5,250,325,900},
			levelSequence = getSkillOrder("QWE"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "RivenCombo"
			},
			{--RUMBLE
			name = "Rumble",
            farmSpells = {{_Q,1},{_W,1}},
			skillRanges = {600,0,850,1700},
			levelSequence = getSkillOrder("QEW"),
			items = APBurstNoMana,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "RumbleCombo"
			},			
			{--RYZE done
			name = "Ryze",
            farmSpells = {{_Q,4},{_W,4},{_E,4},{_R,1}},
			skillRanges = {650,625,649,0},
			levelSequence = getSkillOrder("QWE"),
			items = {"Sorcerer's Shoes","Rod of Ages","Manamune","Frozen Heart","Abyssal Scepter","Will of the Ancients"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "RyzeCombo",
			extra = "RyzeObject"
			},		
			{--SEJUANI
			name = "Sejuani",
            farmSpells = {{_Q,5},{_W,1}},
			skillRanges = {650,350,1000,1175},
			levelSequence = getSkillOrder("QWE"),
			items = TANK_AP,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "SejuaniCombo",
			extra = "SejuaniObject"
			},		
			{--SHACO
			name = "Shaco",
            stealth = {_Q,2},
            farmSpells = {{_W,5}},
			skillRanges = {400,425,625,0},
			levelSequence = getSkillOrder("EQW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "ShacoCombo",
			extra = "ShacoObject"
			},		
			{--SHEN
			name = "Shen",
            farmSpells = {{_Q,4}},
			skillRanges = {475,900,575,0},
			levelSequence = getSkillOrder("EQW"),
			tpE = {575,1500,0.2,200},
			items = TANK_AP,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "ShenCombo",
			extra = "ShenObject"
			},
			{--SHYVANA
			name = "Shyvana",
            farmSpells = {{_Q,1},{_W,1},{_E,5}},
			skillRanges = {350,162.5,925,1000},
			levelSequence = getSkillOrder("EWQ"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "ShyvanaCombo",
			extra = "ShyvanaObject"
			},
			{--SINGED
			name = "Singed",
			skillRanges = {650,1000,125,0},
			levelSequence = getSkillOrder("EQW"),
			items = TANK_AP,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "SingedCombo",
			extra = "SingedObject"
			},			
			{--SION done
			name = "Sion",
            farmSpells = {{_W,4}},
			skillRanges = {550,700,0,550},
			levelSequence = getSkillOrder("EQW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "SionCombo",
			extra = "SionVariables"
			},
			{--SIVIR
			name = "Sivir",
            farmSpells = {{_W,1},{_Q,5}},
			skillRanges = {1000,700,500,600},
			levelSequence = getSkillOrder("QWE"),
			items = {"Berserker's Greaves", "Sanguine Blade", "Phantom Dancer", "Trinity Force", "Black Cleaver", "Runaan's Hurricane"},
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "SivirCombo",
			extra = "SivirObject"
			},
			{--SKARNER
			name = "Skarner",
            farmSpells = {{_Q,1},{_E,5}},
			skillRanges = {350,650,600,350},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "SkarnerCombo",
			extra = "SkarnerObject"
			},			
			{--SONA
			name = "Sona",
            farmSpells = {{_Q,1},{_W,1}},
			skillRanges = {700,1000,1000,900},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "SonaCombo",
			extra = "SonaObject"
			},
			{--SORAKA
			name = "Soraka",
            farmSpells = {{_Q,1},{_E,4}},
			skillRanges = {530,750,725,0},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "SorakaCombo",
			extra = "SorakaObject"
			},
			{--SWAIN
			name = "Swain",
            farmSpells = {{_R,1},{_W,5}},
			skillRanges = {625,900,625,650},
			levelSequence = getSkillOrder("EQW"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "SwainCombo",
			extra = "SwainObject"
			},			
			{--SYNDRA
			name = "Syndra",
            farmSpells = {{_Q,5},{_E,5},{_E,4}},
			skillRanges = {800,925,650,675},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "SyndraCombo",
			extra = "SyndraObject"
			},
			{--TALON done
			name = "Talon",
            farmSpells = {{_W,5}},
			skillRanges = {350,600,700,500},
			levelSequence = getSkillOrder("WQE"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "TalonCombo",
			extra = "TalonObject"
			},
			{--TARIC
			name = "Taric",
            farmSpells = {{_W,1},{_Q,3}},
			skillRanges = {750,200,625,200},
			levelSequence = getSkillOrder("QEW"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "TaricCombo",
			extra = "TaricObject"
			},
			{--TEEMO
			name = "Teemo",
            farmSpells = {{_R,5}},
			skillRanges = {580,0,0,230},
			levelSequence = getSkillOrder("EQW"),
			items = {"Berserker's Greaves","Wit's End","Nashor's Tooth","Kitae's Bloodrazor","Rylai's Crystal Scepter","Runaan's Hurricane"},
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "TeemoCombo",
			extra = "TeemoObject",
			},
			{--THRESH
			name = "Thresh",
			skillRanges = {1075,950,400,450},
			levelSequence = getSkillOrder("QEW"),
			items = TANK_AP,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "ThreshCombo",
			extra = "ThreshObject"
			},	
			{--TRISTANA done
			name = "Tristana",
            farmSpells = {{_Q,1}},
            tpW = {900, 1170, 0.3, 200},
			skillRanges = {700,900,550,645},
			levelSequence = getSkillOrder("EWQ"),
			items = AD_CARRY,
            --items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "TristanaCombo",
			extra = "TristanaObject"
			},	
			{--TRUNDLE done
			name = "Trundle",
            farmSpells = {{_Q,4}},
			skillRanges = {350,900,100,700},
			levelSequence = getSkillOrder("EQW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "TrundleCombo",
			extra = "TrundleObject"
			},	
			{--TRYNDAMERE
			name = "Tryndamere",
            farmSpells = {{_E,5}},
			skillRanges = {0,400,660,0},
			levelSequence = getSkillOrder("EQW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "TryndamereCombo",
			extra = "TryndamereObject"
			},	
			{--TWISTEDFATE
			name = "TwistedFate",
            farmSpells = {{_Q,5}},
			skillRanges = {1450,750,0,550}, --W is autoattack range
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "TwistedFateCombo",
			extra = "TwistedFateObject"
			},	
			{--TWITCH done
			name = "Twitch",
            stealth = {_W,1},
			skillRanges = {0,950,1200,850},
			levelSequence = getSkillOrder("EWQ"),
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "TwitchCombo",
			extra = "TwitchObject"
			},
			{--UDYR done
			name = "Udyr",
            farmSpells = {{_Q,1},{_W,1}},
			skillRanges = {130,130,600,250},
			levelSequence = getSkillOrder("QWE"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "UdyrCombo",
			extra = "UdyrObject"
			},
			{--URGOT needitems
			name = "Urgot",
            farmSpells = {{_Q,5}},
			skillRanges = {1000,0,900,550},
			levelSequence = getSkillOrder("QWE"),
			items = FormChangers,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "UrgotCombo",
			extra = "UrgotObject"
			},			
			{--VARUS done
			name = "Varus",
            farmSpells = {{_E,5}},
			skillRanges = {1000,0,925,1050},
			levelSequence = getSkillOrder("QEW"),
			items = AD_CARRY,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "VarusCombo",
			extra = "VarusObject"
			},
			{--VAYNE done
			name = "Vayne",
            farmSpells = {{_Q,5}},
			skillRanges = {300,0,575,600},
			levelSequence = getSkillOrder("WQE"),
			items = {"Berserker's Greaves","Phantom Dancer","Trinity Force","Sanguine Blade","Zephyr","Frozen Mallet"},
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "VayneCombo",
			extra = "VayneObject"
			},
			{--VEIGAR
			name = "Veigar",
            farmSpells = {{_Q,4},{_W,5}},
			skillRanges = {650,900,650,650},
			levelSequence = getSkillOrder("WQE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "VeigarCombo",
			extra = "VeigarObject"
			},			
            {--VELKOZ
			name = "Velkoz",
            farmSpells = {{_W,5}},
			skillRanges = {1050,1050,850,1575},
			levelSequence = getSkillOrder("WQE"),
			items = APBurst,
			tsRange = 1350,
			damageType = DAMAGE_MAGIC,
			action = "VelkozCombo",
			extra = "VelkozObject"
			},			
            {--VI done
			name = "Vi",
            farmSpells = {{_E,1}},
			skillRanges = {725,0,600,700},
			levelSequence = getSkillOrder("QWE"),
			items = TANK_AD,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "ViCombo",
			extra = "ViObject"
			},		
			{--VIKTOR
			name = "Viktor",
			skillRanges = {600,625,700,700},
			levelSequence = getSkillOrder("QEW"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "ViktorCombo",
			extra = "ViktorObject"
			},	
			{--VLADIMIR
			name = "Vladimir",
            farmSpells = {{_Q,4},{_E,1}},
			skillRanges = {600,0,610,700},
			levelSequence = getSkillOrder("QEW"),
			items = APBurstNoMana,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "VladimirCombo",
			extra = "VladimirObject"
			},	
			{--VOLIBEAR
			name = "Volibear",
            farmSpells = {{_E,1}},
			skillRanges = {0,400,425,300},
			levelSequence = getSkillOrder("WEQ"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "VolibearCombo",
			extra = "VolibearObject"
			},	
			{--WARWICK
			name = "Warwick",
            farmSpells = {{_Q,4},{_W,1}},
			skillRanges = {400,1250,1500,700},
			levelSequence = getSkillOrder("QWE"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "WarwickCombo",
			extra = "WarwickObject"
			},	
			{--MonkeyKing
			name = "MonkeyKing",
            farmSpells = {{_Q,1},{_E,4}},
			skillRanges = {350,0,625,162.5},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "MonkeyKingCombo",
			extra = "MonkeyKingObject"
			},	
			{--XERATH
			name = "Xerath",
            farmSpells = {{_Q,5}},
			skillRanges = {900,0,600,900},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "XerathCombo",
			extra = "XerathObject"
			},	
			{--XINZHAO done
			name = "XinZhao",
            farmSpells = {{_Q,1},{_E,4},{_W,1}},
			skillRanges = {350,350,600,187.5},
			levelSequence = getSkillOrder("QEW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "XinZhaoCombo"
			},
			{--YORICK
			name = "Yorick",
            farmSpells = {{_Q,1},{_W,5},{_E,4}},
			skillRanges = {350,600,550,900},
			levelSequence = getSkillOrder("QWE"),
			items = TANK_AD,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "YorickCombo",
			extra = "YorickObject"
			},	
			{--ZED
			name = "Zed",
            farmSpells = {{_E,1}},
			skillRanges = {900,550,290,625},
			levelSequence = getSkillOrder("EQW"),
			items = AD_BRUISER,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "ZedCombo",
			extra = "ZedObject"
			},	
			{--Zac
			name = "Zac",
            farmSpells = {{_Q,5},{_W,1}},
			skillRanges = {550,350,1150,0},
			levelSequence = getSkillOrder("QWE"),
			items = TANK_AD,
			tsRange = 1150,
			damageType = DAMAGE_PHYSICAL,
			action = "ZacCombo",
			extra = "ZacObject"
			},	
			{--ZIGGS
			name = "Ziggs",
            farmSpells = {{_Q,5},{_W,5},{_E,5}},
			skillRanges = {850,1000,900,5300},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "ZiggsCombo",
			extra = "ZiggsObject"
			},	
			{--ZILEAN
			name = "Zilean",
            farmSpells = {{_Q,4},{_W,1}},
			skillRanges = {700,0,700,780},
			levelSequence = getSkillOrder("QWE"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "ZileanCombo",
			extra = "ZileanObject"
			},	
			{--ZYRA done
			name = "Zyra",
            farmSpells = {{_Q,5},{_W,5},{_E,5}},
			skillRanges = {825,825,1000,700},
			levelSequence = getSkillOrder("QEW"),
			items = APBurst,
			tsRange = 1150,
			damageType = DAMAGE_MAGIC,
			action = "ZyraCombo",
			extra = "ZyraObject"
			},				
		


		}
		--Default values if champ is not found (so no errors..)
		ts = TargetSelector(TARGET_LOW_HP_PRIORITY, 1150, DAMAGE_PHYSICAL, false)
		action = "defaultCombo"
		skillRanges = {800,800,800,800}
		levelSequence = {1,2,3,3,3,4,1,3,3,1,4,1,1,2,2,4,2,2}
		defaultItems = AD_BRUISER
		tsRange = 1300
		damageType = DAMAGE_PHYSICAL
		
		--Compute information here
		for i, champion in pairs(champions) do
			if champion.name == myHero.charName then
				qRange = champion.skillRanges[1]
				wRange = champion.skillRanges[2]
				eRange = champion.skillRanges[3]
				rRange = champion.skillRanges[4]
				if champion.skillRanges[5] and champion.skillRanges[6] and champion.skillRanges[7] and champion.skillRanges[8] then
				qRange2 = champion.skillRanges[5]
				wRange2 = champion.skillRanges[6]
				eRange2 = champion.skillRanges[7]
				rRange2 = champion.skillRanges[8]
				end
				levelSequence = champion.levelSequence
				--Add items
				if type(champion.items) == "table" then
				items = {}
				for j,x in ipairs(champion.items) do items[j] = x  end
				else
				items = defaultItems
				end
				--Set stealthkey if present
                if champion.stealth then
				stealthKey = {champion.stealth[1],champion.stealth[2]}
                end
				
                --TEST AUTO PREDICTION
                tpQ = getTP(qRange,_Q)
                tpW = getTP(wRange,_W)
                tpE = getTP(eRange,_E)
                tpR = getTP(rRange,_R)
                
                --Set TS
				ts = TargetSelector(TARGET_LOW_HP_PRIORITY, tsRange, champion.damageType, false)
				--SET TP [vip] if they have one
				if champion.tpVIP ~= nil then
					tp = TargetPredictionVIP(champion.tpVIP[1],champion.tpVIP[2],champion.tpVIP[3],champion.tpVIP[4])
				end
				if champion.tpQ ~= nil then
					tpQ = TargetPredictionVIP(champion.tpQ[1],champion.tpQ[2],champion.tpQ[3],champion.tpQ[4])
				end
				if champion.tpW ~= nil then
					tpW = TargetPredictionVIP(champion.tpW[1],champion.tpW[2],champion.tpW[3],champion.tpW[4])
				end
				if champion.tpE ~= nil then
					tpE = TargetPredictionVIP(champion.tpE[1],champion.tpE[2],champion.tpE[3],champion.tpE[4])
				end
				if champion.tpR ~= nil then
					tpR = TargetPredictionVIP(champion.tpR[1],champion.tpR[2],champion.tpR[3],champion.tpR[4])
				end
				
                
                
				--Which combo to perform
				if _ENV[champion.action] then
				action = champion.action
				PrintChat("[Battle] ".. myHero.charName .. " Combo Loaded")
				else
				PrintChat("[Battle] Using generic fighting set.")
				end
				
				if champion.extra ~= nil then
				extra = champion.extra
				end
				AdditionalInfo(champion.name) --For special champion requirements
				
                --Farm minions
                if champion.farmSpells then useSpells = champion.farmSpells end
				if champion.damageType == DAMAGE_PHYSICAL or useSpells then
				killMinions = true
				else
				killMinions = false
				end
				break -- End info gathering
			else
			items = defaultItems
			end
		end
	
	end
	function getTP(range,skill)
        local delay = (250+(myHero:GetSpellData(skill).delayCastOffsetPercent * 1000 + 500) * 0.5)/1000
        return TargetPredictionVIP(range,myHero:GetSpellData(skill).missileSpeed,delay,myHero:GetSpellData(skill).lineWidth)
    end
	function AdditionalInfo(hero)
	--Akali
		if hero == "Akali" then
			scriptActive = false
			enemyhaveQ = false 
			qInAir  = true 
			QparticleDist = 70
			WeHaveEnergy = false
		end
	--Amumu
		if hero == "Amumu" then
			wOn = false
			championPassives = function()
				if wOn then
					if not ts.target and myHero:CanUseSpell(_W) == READY then
						CastSpell(_W)

					end
				end
			end
		end
	--Nunu
		if hero == "Nunu" then
			championPassives = function()
					if myHero.casting == 1 then return end
					if  ts.target and myHero:CanUseSpell(_E) == READY and DistanceBetweenLOE(ts.target,myHero,eRange) then
						CastSpell(_E,ts.target)
					end
					if myHero:CanUseSpell(_W) == READY then CastSpell(_W,myHero) end
					if not ts.target and (myHero:CanUseSpell(_Q) == READY or myHero:CanUseSpell(_E)) then
						for i,a_minion in ipairs(minionTable) do
							if a_minion and minionIsValid(a_minion) and a_minion.health > 0 then
								if myHero:CanUseSpell(_Q) == READY and DistanceBetweenLOE(myHero,a_minion, qRange) and a_minion.visible then
									if myHero.health ~= myHero.maxHealth then
									CastSpell(_Q,a_minion) return
									end
								end
								if myHero:CanUseSpell(_E) == READY and DistanceBetweenLOE(myHero,a_minion, eRange) and a_minion.visible then
									if myHero.health ~= myHero.maxHealth then
									CastSpell(_E,a_minion) return
									end
								end
								
								
							else table.remove(minionTable, i) i = i - 1 end
						end
					end
				
			end
		end
	--Shen
		if hero == "Shen" then
			championPassives = function()
				if (myHero:CanUseSpell(_R) == READY) then
					for _,ally in ipairs(allies) do
						if ally and not ally.isMe and ally.health/ally.maxHealth < 0.3 and enemiesNearTarget(ally) > 2 then
							CastSpell(_R,ally)
						end
					end
				end
			end
		end
	--Anivia
		if hero == "Anivia" then
			 frozen = false
			 range1 = 650
			 range2 = 1100
			 speed = 0.845
			 delay = 300 
			 smoothness = 50
			 lastfrozen = 0
			 justfrozen = true
			 hit = false
			 start = false
			 ball = nil
			 starthit = 0
			 lastcast = 0
			 freezetimets = 0
			 freezetime = {}
			 lastQ = 0
			 for i=1, heroManager.iCount do freezetime[i] = 0 end
		end
	--Annie
		if hero == "Annie" then
			existTibbers = false
			
			championPassives = function()
                if state ~= "capping" and state ~= "recalling" and not ts.target and myHero:CanUseSpell(_E) == READY then
					CastSpell(_E)
				end
			end
			
		end
	--Ashe
		if hero == "Ashe" then
			aaParticles = {"bowmaster_BasicAttack_tar", "bowmaster_frostShot_cas", "bowmaster_frostShot_mis", "bowmasterbasicattack_mis"}
		end
	
	--Irelia
		if hero == "Irelia" then
			championPassives = function()
                if os.clock() > (lasthiten or 0) + 6 then
                    hitendmg = 0
                end
				for i,a_minion in ipairs(minionTable) do
					if a_minion and minionIsValid(a_minion) and a_minion.health > 0 then
						if DistanceBetweenLOE(myHero,a_minion, qRange) and a_minion.visible then
							if getDmg("Q",a_minion,myHero) + (hitendmg or 0) > a_minion.health then
							CastSpell(_Q,a_minion) return
							end
						end
					else table.remove(minionTable, i) i = i - 1 end
				end
			end
		end
	--Vayne
		if hero == "Vayne" then
			aaParticles = {"vayne_ult_mis.troy", "vayne_ult_mis", "vayne_basicAttack_mis", "vayne_critAttack_mis", "vayne_critAttack_tar"}
			championPassives = function()
				if state == "retreat" and (myHero:CanUseSpell(_Q) == READY) then
				if ts.target then
					CastSpell(_Q,makeDistance(myHero,ts.target,700))
				else
					CastSpell(_Q,heroBase.x,heroBase.z)
				end
				end
			end
		end

	--Blitzcrank
		if hero == "Blitzcrank" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_W) == READY) then
					CastSpell(_W)
				end
			end
		end
	--Darius
		if hero == "Darius" then
		--Variables
			hemoTable = {
			[1] = "darius_hemo_counter_01.troy",
			[2] = "darius_hemo_counter_02.troy",
			[3] = "darius_hemo_counter_03.troy",
			[4] = "darius_hemo_counter_04.troy",
			[5] = "darius_hemo_counter_05.troy",
			}
			
			damageTable = {
			Q = { base = 35, baseScale = 35, adRatio = 0.7, },
			R = { base = 70, baseScale = 90, adRatio = 0.75, },
			}
			
			
			checkBuffForUlti = {
			{name="Tryndamere", spellName="undyingRage", enabled=false, spellType=0, spellLevel=1, duration=5000, spellParticle="undyingrage_glow"},
			{name="Kayle", spellName="eyeForEye", enabled=false, spellType=0, spellLevel=1, duration=3000, spellParticle="eyeforaneye"},
			{name="Zilean", spellName="nickOfTime", enabled=false, spellType=0, spellLevel=1, duration=7000, spellParticle="nickoftime_tar"},
			{name="Nocturne", spellName="shroudOfDarkness", enabled=false, spellType=0, spellLevel=1,duration=1500,spellParticle="nocturne_shroudofdarkness_shield_cas_02"},
			{name="Blitzcrank", spellName="manaBarrier", enabled=false, spellType=1, spellLevel=1, duration=10000, spellParticle="manabarrier"},
			{name="Sivir", spellName="spellShield", enabled=false, spellType=0, spellLevel=1, duration=3000, spellParticle="spellblock_eff"}
			}

			 havocPoints = 3  
			 wDmgRatioPerLvl = 0.2
			 rDmgRatioPerHemo = 0.2
			 hemoTimeOut = 5000
			 targetFindRange = 80 
			 smoothMultR = 1.0
			 smoothStaticR = -2
			 smoothStaticPerLvl = true
			 smoothDisabledBeforeLvl = 12
			 qBladeRange = 270
			 enemyToAttack = nil
			 enemyTable = {}
			 scriptActive = false
			 cActive = false
			
			for i=0, heroManager.iCount, 1 do
			local playerObj = heroManager:GetHero(i)
				if playerObj and playerObj.team ~= myTEAM then
					playerObj.hemo = { tick = 0, count = 0, }
					playerObj.pauseTickQ = 0
					playerObj.pauseTickR = 0
					playerObj.canBeUlted = true
					playerObj.immuneTimeout = 0
					playerObj.shield = 0
					table.insert(enemyTable,playerObj)
					for i=1, #checkBuffForUlti, 1 do
						if checkBuffForUlti[i].name == playerObj.charName then
							checkBuffForUlti[i].enabled = true
							--PrintChat(checkBuffForUlti[i].spellName.." check enabled")
						end
					end
				end
			end
		--Functions
			function CanUltiEnemy(target)
				for i, enemy in pairs(enemyTable) do
					if target.networkID == enemy.networkID then
						if enemy.canBeUlted == false and enemy.immuneTimeout < GetTickCount() then
							enemy.canBeUlted = true
							enemy.immuneTimeout = 0
						end
						return enemy.canBeUlted
					end
				end
			end
			function getShieldValue(enemy,spellName)
				if spellName == "manaBarrier" then
					return enemy.mana*0.5
				end
				return 0
			end
			function GetDuration(spellName,spellLevel)
				if spellName == "undyingRage" then return 5000 end
				if spellName == "eyeForEye" then return 1500+500*spellLevel end
				if spellName == "nickOfTime" then return 7000 end
				if spellName == "shroudOfDarkness" then return 1500 end
			end
			
			
		end
		if hero == "Diana" then
			MoonLightEnemy = {}
			
		end
	--Evelynn
		if hero == "Evelynn" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_W) == READY) then
					CastSpell(_W)
				end
				if ts.target and GetDistance(ts.target) <= qRange and (myHero:CanUseSpell(_Q) == READY) then
					CastSpell(_Q)
				end
			end
		end
	--Ezreal
		if hero == "Ezreal" then
			championPassives = function()
                if not ts.target and myHero.mana > 300 and (GetInventorySlotItem(3073) or GetInventorySlotItem(3008) or GetInventorySlotItem(3007)) and state == "moving" then CastSpell(_Q,myHero.x,myHero.z) CastSpell(_W,myHero.x,myHero.z) end
				if ts.target and (myHero:CanUseSpell(_Q) == READY) and GetDistance(ts.target,myHero) < qRange then
                    Qpredict = tpQ:GetPrediction(ts.target)
                    if Qpredict ~= nil then
                        if QReady then CastSpell(_Q,Qpredict.x,Qpredict.z) end
                    end
				end
			end
		end
    --Khazix
		if hero == "Khazix" then
			championPassives = function()
                if myHero.level == 6 and abilityLevel == myHero.level+1 then LevelSpell(getSpellSlot(2)) end
                if myHero.level == 11 and abilityLevel == myHero.level+1 then LevelSpell(getSpellSlot(3)) end
                if myHero.level == 16 and abilityLevel == myHero.level+1 then LevelSpell(getSpellSlot(1)) end
			end
		end   
    --Draven
        if hero == "Draven" then
			lastW = 0 --This is for making sure you don't spam W everytime you catch an axe to conserve mana
			qreticleName = "Draven_Q_reticle_self.troy"
			reticles = {}
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_W) == READY) then
					CastSpell(_W)
				end
				
			end
		end
	--Global Ultis
		if hero == "Ezreal" or hero == "Gangplank" or hero == "Karthus" or hero == "Draven" or hero == "Ashe" or hero == "Ziggs" then
		enemyTable = {}
		for i=0, heroManager.iCount, 1 do
			local playerObj = heroManager:GetHero(i)
				if playerObj and playerObj.team ~= myTEAM then
					table.insert(enemyTable,playerObj)	
				end
			end
		end
	--Gangplank
		if hero == "Gangplank" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_E) == READY) then
					CastSpell(_E)
				end
			end
		end
	--Garen
		if hero == "Garen" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_Q) == READY) then
					CastSpell(_Q)
				end
			end
		end
	--Heimerdinger
		if hero == "Heimerdinger" then
			--Variables
			rangeAroundTurret = 450 --Range where heimer's turrets are placed
			
			--Functions
			deadActions = function()
				for i,object in ipairs(turrets) do
					if not isHeimerTurret(object) then
						table.remove(turrets, i) i = i - 1 
					end
				end
			end
			
			championPassives = function()
					for i,object in ipairs(turrets) do
						if not isHeimerTurret(object) then
							table.remove(turrets, i) i = i - 1 
						end
					end
			
				--Turret placing
				if #turrets <= 2 and (myHero:CanUseSpell(_Q) == READY) then
					place,turretX,turretY = placeTurretLocation()
					if place then
					CastSpell(_Q,turretX,turretY)
					state = "placing turret"
					return "placing turret"					
					end
				end
				
				if state == "retreat" then
					if (myHero:CanUseSpell(_Q) == READY) then
						CastSpell(_Q,myHero.x,myHero.z)
						if (myHero:CanUseSpell(_R) == READY) then CastSpell(_R) end
					end
				end
			end
			isHeimerTurret = function(object)
				return object and object.valid and not object.dead and object.name == "H-28G Evolution Turret"
			end		
			placeTurretLocation = function()
				local tower,condition,focus = getClosestTowerState()
				if not tower and focus then
					local positionX,positionZ = 6920,6800
					if myHero.x > 0 then positionX,positionZ = enemyBase.x, enemyBase.z end
					local x,z = getPositionOfPlacement(positionX,positionZ,focus)			
					for i,turret in ipairs(turrets) do
						if isHeimerTurret(turret) then
							if  math.sqrt((x-turret.x)^2+(z-turret.z)^2) < 500 then
								return false
							else
								
							end
						else table.remove(turrets, i) i = i - 1
						end
					end
						
						return true, x, z			
					
				else
				return false
				end
			end
			getPositionOfPlacement = function(x,z,tower)
				x1,z1 = tower.x,tower.z
				gradient = (z - z1)/( x - x1)
				if x1 > x then
					return   -((math.cos(math.atan(gradient))) * rangeAroundTurret) + x1,-((math.sin(math.atan(gradient))) * rangeAroundTurret) + z1
				else
					return  ((math.cos(math.atan(gradient))) * rangeAroundTurret) + x1,((math.sin(math.atan(gradient))) * rangeAroundTurret) + z1
				end
			end
			turrets = {}
			for i = 0, objManager.maxObjects do
				local obj = objManager:getObject(i)
				if isHeimerTurret(obj) then
					table.insert(turrets,obj)
				end
			end
			
		end
	
	--Katarina
		if hero == "Katarina" then
			timeq = 0
			lastqmark = 0
			lastulti = 0
			championPassives = function()
				if ts.target then
					if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_W) == READY) and GetDistance(ts.target) < wRange then
						CastSpell(_W)
					end
				end
			end
		end
	--Kennen
		if hero == "Kennen" then
			kennenE = 0
			championPassives = function()
				if GetTickCount() > kennenE and (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_E) == READY) then
					CastSpell(_E)
					kennenE = GetTickCount() + 4000
				end
			end
		end
	--KogMaw
		if hero == "KogMaw" then
			lastR = 0
			
		--FUNCTIONS
			deadActions = function()
				if ts.target then
					heroMove(ts.target.x,ts.target.z)
				end
			end
			
			
			
		end
	--Lulu
		if hero == "Lulu" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_W) == READY) then
					CastSpell(_W,myHero)
				end
			end
		end
    --Lux
		if hero == "Lux" then
			championPassives = function()
				if ts.target and (state == "retreat") and (myHero:CanUseSpell(_Q) == READY) then
					CastSpell(_Q,ts.target.x,ts.target.z)
				end
                if ts.target and (state == "retreat") and (myHero:CanUseSpell(_E) == READY) then
					CastSpell(_E,(ts.target.x+myHero.x)*(2/3)(ts.target.z+myHero.z)*(2/3))
				end
			end
		end
    --Nidalee
		if hero == "Nidalee" then
			championPassives = function()
				--Stuff like teemo w, kennen's e, etc.
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_W) == READY) then
                    if myHero.range < 300 then
                        CastSpell(_W)
                    elseif myHero:CanUseSpell(_R) == READY then
                        CastSpell(_R)
                    end
				end
			end
		end
    
    --Poppy
		if hero == "Poppy" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_W) == READY) then
					CastSpell(_W)
				end
			end
		end
	
	--Rammus
		if hero == "Rammus" then
			lastQ = 0
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_Q) == READY) and GetTickCount() > lastQ then
					CastSpell(_Q)
					lastQ = GetTickCount() + 7000
				end
			end
		end
	--Riven
		if hero == "Riven" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_E) == READY) then
					--CastSpell(_E)
				end
			end
		end
	--Shyvana
		if hero == "Shyvana" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_W) == READY) then
					CastSpell(_W)
				end
			end
		end
	--Skarner
		if hero == "Skarner" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_W) == READY) then
					CastSpell(_W)
				end
			end
		end
	--Sona
		if hero == "Sona" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_E) == READY) then
					CastSpell(_E)
				end
			end
		end
	--Teemo
		if hero == "Teemo" then
			championPassives = function()
				--Stuff like teemo w, kennen's e, etc.
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_W) == READY) then
					CastSpell(_W)
				end
			end
		end
	--Volibear
		if hero == "Volibear" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_Q) == READY) then
					CastSpell(_Q)
				end
			end
		end
	--Zilean
		if hero == "Zilean" then
			championPassives = function()
				if (state == "moving" or state == "retreat") and (myHero:CanUseSpell(_E) == READY) then
					CastSpell(_E)
				end
			end
		end
	
	--Warwick
		if hero == "Warwick" then
			warwickE = false
		end
	--Sion
		if hero == "Sion" then
		--Variables	
			sionECasted = false --Hopefully it isn't on when reloaded.
		end

	--Ryze
		if hero == "Ryze" then
		--Variables
			lastcast = _E
            championPassives = function()
				if ts.target and GetDistance(ts.target) < 600 then
					Cast(_Q,4)
                    Cast(_W,4)
                    Cast(_E,4)
                    
				end
			end
		end
	--Vi
		if hero == "Vi" then
		--Variables
		qTick = 0
		castQ = false
		TimeIsRight = false
		swingDelay  = 0.24
		EMaxRange   = 600
		EMinRange   = 125
		ScanRange   = 700
		RRange      = 700
		RWidth      = 200
		DFGSSlot    = nil
		DFGReady    = false
		sexyahhahh  = 0 
		iPressedQ   = false
		buffer      = 0 
		debugTime   = 0
		targetX,targetZ = 0,0
		lastBasicAttack = 0   		
		invisibleTime   = 300 
		
		
		--Functions
		function CastSpellQ() --Cast Vi's Q workaround since it's bugged. Ty eXtragoZ!
			if myHero:CanUseSpell(_Q) == READY then
				if not iPressedQ and GetTickCount() - qTick + invisibleTime > HoldForHowLong(targetX, targetZ) then
					CastSpell(_Q, targetX, targetZ)
					CastSpell(10)
					swing = 0
					qTick = GetTickCount()
				end

				if iPressedQ and GetTickCount() - qTick + invisibleTime > HoldForHowLong(targetX, targetZ) then
					CastSpell(_Q, targetX, targetZ)
					qTick = GetTickCount()
				end
			end
		end
		function pythag(x,y)

			return math.floor(math.sqrt((x)^2 + (y)^2))	

		end
		function HoldForHowLong(x,z)
				result = 0
				distance = pythag(myHero.x - x, myHero.z - z) + buffer
				if distance < 250 then
					return result
				elseif distance > 725 then
					return result + 1250
				else
					result = (distance-250)/0.38
					return result
				end
				
			end
		end
	
	end
		
	function championObjects(role,object)
		if role == "Create" then
		--Ashe, Vayne
			if extra == "AsheObject" or extra == "VayneObject" then
				for _, v in pairs(aaParticles) do
					if object.name:lower():find(v:lower()) then
						lastAttack = GetTickCount()
						
					end
				end
				
			end
		--Akali
			if extra == "AkaliObject" then
				if object.name:find("akali_markOftheAssasin_marker_tar.troy") then --we get this particle if our Q hit something
					qInAir = false --if Q hit something, then Q is not on the air
				if object and ts.target ~= nil and GetDistance(object,ts.target) <= QparticleDist then
					enemyhaveQ = true --Q hit target
				end
			end
			end
		--Amumu
			if extra == "AmumuObject" then
				if object.name == "Despair_buf.troy" then
					wOn = true
					
				end
			end
		--Anivia
			if extra == "AniviaObject" then
				if object.name:find("Global_Freeze") then
					for i=1, heroManager.iCount do
						local enemy = heroManager:GetHero(i)
						if enemy.team ~= myTEAM and GetDistance(object, enemy) < 100 then
							freezetime[i] = GetTickCount() 
						end
					end
				end
				if object.name:find("FlashFrost_mis") then
					ball = object
					start = true
				end
			end
		--Annie
			if extra == "AnnieObject" then
				if object.name == "BearFire_foot.troy" then existTibbers = object end
			end
		--Darius
			if extra == "DariusObject" then
				--Immunity Check
				for i=1, #checkBuffForUlti, 1 do
					if string.find(string.lower(object.name),checkBuffForUlti[i].spellParticle) and checkBuffForUlti[i].enabled == true then
						for i, enemy in pairs(enemyTable) do
							if GetDistance(enemy,object) < 30 then
								if checkBuffForUlti[i].spellType == 0 then
									enemy.canBeUlted = false
									enemy.immuneTimeout = GetTickCount()+checkBuffForUlti[i].duration
								elseif checkBuffForUlti[i].spellType == 1 then
									enemy.shield = enemy.shield + getShieldValue(checkBuffForUlti[i].spellName)
								end
							end
						end
					end
				end
				--Hemo check
				if string.find(string.lower(object.name),"darius_hemo_counter") then
					for i, enemy in pairs(enemyTable) do
						if enemy and not enemy.dead and enemy.visible and GetDistance(enemy,object) <= targetFindRange then
							for k, hemo in pairs(hemoTable) do
								if object.name == hemo then 
									enemy.hemo.tick = GetTickCount()
									enemy.hemo.count = k
								end
							end
						end
					end
				end		
			
			
			end
		--Diana
			if extra == "DianaObject" then
				 if object.name:find("Diana_Q_moonlight_champ.troy") then
					for i = 1, heroManager.iCount do
						local enemy = heroManager:GetHero(i)
						if enemy.team ~= myHero.team and GetDistance(object, enemy) < 50 then
							MoonLightEnemy[i] = true
						end
					end
				end
			end
		--Draven
			if extra == "DravenObject" then
				if object.name == qreticleName then
					table.insert(reticles, object)
				end
			end
		--Katarina
			if extra == "KatarinaObject" then
				if object.name == "katarina_deathLotus_mis.troy" then ulti = true lastulti = GetTickCount() end
				if object.name:find("katarina_daggered") then lastqmark = GetTickCount() end
			end
		
		--Malzahar
			if extra == "MalzaharObject" then
				if object~= nil and object.name:find("AlZaharNetherGrasp_tar.troy") then
                    UltStarted = true
				end 
			end
		--Vi
			if extra == "ViObject" then
				if object.name == "Vi_Q_Channel_L.troy" then iPressedQ = true end
			end
		--Heimerdinger
			if extra == "HeimerdingerObject" then
				if isHeimerTurret(object) then table.insert(turrets,object) end
			end
		end	
        --[[DELETE]]--
		if role == "Delete" then
		--Amumu
			if extra == "AmumuObject" then
				if object.name == "Despair_buf.troy" then
					wOn = false
					
				end
			end
		--Anivia
			if extra == "AniviaObject" then
				if object.name:find("FlashFrost_mis") then
					start = false
					ball = nil
				end
			end
		--Annie
			if extra == "AnnieObject" then
				if object.name == "BearFire_foot.troy" then existTibbers = false end
			end
		--Darius
			if extra == "DariusObject" then
				for i=1, #checkBuffForUlti, 1 do
					if string.find(string.lower(object.name),checkBuffForUlti[i].spellParticle) and checkBuffForUlti[i].enabled == true then
						for i, enemy in pairs(enemyTable) do
							if GetDistance(enemy,object) < 30 then
								if checkBuffForUlti[i].spellType == 0 then
									enemy.canBeUlted = true
									enemy.immuneTimeout = 0
								elseif checkBuffForUlti[i].spellType == 1 then
									enemy.shield = enemy.shield - getShieldValue(checkBuffForUlti[i].spellName)
									if enemy.shield < 0 then
										enemy.shield = 0
									end
								end
							end
						end
					end
				end
			end
		--Diana
			if extra == "DianaObject" then
				if object.name:find("Diana_Q_moonlight_champ.troy") then
					for i = 1, heroManager.iCount do
						local enemy = heroManager:GetHero(i)
						if enemy.team ~= myHero.team and GetDistance(object, enemy) < 50 then
							MoonLightEnemy[i] = false
						end
					end
				end
			end
		--Draven
			if extra == "DravenObject" then
				if object.name == qreticleName then
					table.remove(reticles, 1)
				end
			end
		--Malzahar
			if extra == "MalzaharObject" then
			    if obj~= nil and obj.name:find("AlZaharNetherGrasp_tar.troy") then
					UltStarted = false
				end
			end
		--Vi
			if extra == "ViObject" then
				if object.name == "Vi_Q_Channel_L.troy" then iPressedQ = false end
			end

		end
				
	end
	
	function captureCount()
		local count = 0
		if top 			== 1 then count = count + 1 end
		if midLeft 		== 1 then count = count + 1 end
		if midRight 	== 1 then count = count + 1 end
		if botLeft 		== 1 then count = count + 1 end
		if botRight 	== 1 then count = count + 1 end
		return count
	end

	--[[ FUNCTIONS ]]--
	--COOP AI or PVP check [coop = true, pvp = false]
	function isBotGame()
		for _, enemy_bot in ipairs(enemies) do
			if enemy_bot and not (string.find(enemy_bot.name," Bot") ~= nil) then
				--Not a bot game
				return false
			end
		end
		--Bot game
		return true
	end
	
	--Math functions--
	function DistanceBetweenLOE(pointONE,pointTWO,distance) --Less or equal
		if type(pointTWO) == "number" then
			return (GetDistanceSqr(pointONE,myHero) <= pointTWO^2)
		else
			return (GetDistanceSqr(pointONE,pointTWO) <= distance^2)
		end
	end
	--Game settings
	function GetGameSettings()
		local path = GAME_PATH:sub(1, GAME_PATH:find("\\RADS")) .. "Config\\game.cfg"
		return ReadIni(path)
	end
    function OnWndMsg(msg,key)
		if msg == WM_LBUTTONDOWN and CursorIsUnder(button_x,button_y,button_w,button_h) then
			changeMode()
		end
	end
	function changeMode()
		if not manual then manual = true
			setButtonText("Manually playing",4290427578 )
            abilityLevel = nil
		else manual = false
			setButtonText("Manual Override",RGBA(255, 0, 0, 200))
		end
	end
	--Draw functions--
	function OnDraw()
    
    
        if topPointMinimap then
            DrawText("TOP",16,topPointMinimap.x - 9, topPointMinimap.y - 5, 0xFFFFFF00)
        end
		myHero=GetMyHero()
		
			local hash = (myHero.x + myHero.y + myHero.z)
			if myPOSITIONhash == hash then
				myHeroIsIdle = true
			else
				myHeroIsIdle = false
				myPOSITIONhash = hash
				lastKnownNotIdle = os.clock()
				
			end
		
		if myHeroIsIdle then
			idleTotalTime = os.clock() - lastKnownNotIdle
			if idleTotalTime > 9000 then Quit() end --Close client and bot will restart game
		end
		
		timeDisconnected = os.clock() - lastRecvPacketTime
		if not CHECK_DC then timeDisconnected = 0 end
		
		local gameSettings = GetGameSettings()
		Width, Height = gameSettings.General.Width, gameSettings.General.Height 
			
		height = (Height or 0)
		width = (Width or 0)
		x = width / 2 
		y = height-600
		boxWidth = 280
		boxHeight = 210
		button_x = width - boxWidth
		button_y = 5+y+boxHeight
		button_w = boxWidth
		button_h = 30

			gameOver = GetGame().isOver
            
            local kda = string.format("KDA: %s / %s / %s",myHero.kills,myHero.deaths,myHero.assists)
			DrawRectangle(width - boxWidth, 0, 140, 30, RGBA(0, 0, 0, 150))
            DrawText(kda,20,width - boxWidth+10,10,RGBA(0, 255, 0, 255)) 
            
            
            
			DrawRectangle(width - boxWidth, y, boxWidth, boxHeight, RGBA(0, 0, 0, 100))
			DrawRectangle(button_x,button_y,button_w,button_h, RGBA(0, 0, 0, 150))
			DrawText(buttonText,20,(width - 205),button_y+5,buttonColor) 
			spacing = 20
			if bot_game then
			DrawText("KBot "..mapName.." [COOP]",20,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 200))  
			else
			DrawText("KBot "..mapName.." [PVP]",20,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 200))  
			end
			increaseSpacing(30)
			DrawLine(width - boxWidth, y + spacing, width, y + spacing, 3, RGBA(0, 255, 0, 100))
			increaseSpacing(10)
			if gameOver then
			increaseSpacing(40)
			DrawText("GAME OVER",20,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 200))  
			increaseSpacing(20)
			if os.clock() > timeNow + 1 then
			timeNow = os.clock()
			timeLeft = Quit()
			if timeLeft then
				closeText = "Closing in..."..tostring(timeLeft)
			end
			end
			if closeText then
			DrawText(closeText,18,width - boxWidth + 10,y + spacing,RGBA(255, 0, 0, 200))  
			end
			return end
		   --[[
		   if timeDisconnected > 1 and 1==0 then 
		   increaseSpacing(20)
		   STOP_ACTIVITY = true
		   DrawText("Did you dc? >"..tostring(timeDisconnected),15,width - boxWidth + 10,y + spacing,4294967280)  
		   if timeDisconnected > 180 then Quit() return end
		   return else STOP_ACTIVITY = false end]]
           
		   if GetInGameTimer() > 30 then
			   if item_state then
				DrawText("Items ["..item_state.."] next buy -> ["..tostring(itemName(items[buyIndex])).."]",15,width - boxWidth + 10, y + spacing,RGBA(255,80,0,255)) increaseSpacing(20) 
			   end
               
               if display_custom_items then
				DrawText("Item build: "..items_text,15,width - boxWidth + 10, y + spacing,RGBA(255,80,0,255)) increaseSpacing(20) 
			   end
               if display_custom_skills then
				DrawText("Skill build: "..skill_text,15,width - boxWidth + 10, y + spacing,RGBA(255,80,0,255)) increaseSpacing(20) 
			   end
			   if c_role then DrawText("Role: "..c_role,15,width - boxWidth + 10, y + spacing,RGBA(0,255,0,255)) increaseSpacing(20) end
			   if defense_roles then DrawText("Sub-Role: "..defense_roles,15,width - boxWidth + 10, y + spacing,RGBA(0,255,0,255)) increaseSpacing(20) end
					DrawText("Action: "..state,15,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 255))  
					increaseSpacing(20)
					if not myHero.dead and escape_to then
					DrawText("Closest safe point: "..(escape_name or "Base"),15,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 255))  
					end
					increaseSpacing(30)
					if GetInGameTimer() < 120 then
					DrawText("If you like my bot, please +rep the thread.",15,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 255))  
					increaseSpacing(20)
					else
					if myHero.charName == "Heimerdinger" then
					DrawText("Heimer turrets: " ..#turrets,15,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 255))  
					increaseSpacing(20)
					end
					
					
					
					DrawText("Captured "..captureCount().."/5 points",15,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 255))  
					increaseSpacing(20)
					
					end
					
					
					
					if myHero.dead and deathTimer then
				deathTime = math.floor(myHero.deathTimer + (((deathTimer - GetTickCount())/1000) + 1))
				DrawText("Respawn in: "..deathTime,15,width - boxWidth + 10,y + boxHeight - 20,RGBA(0, 255, 0, 255))  
				else
				gotTimeDeath = false
				end
			else
				DrawText("Welcome! Thank you for purchasing KevinBot.",15,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 200))  
				increaseSpacing(20)
				DrawText("I hope you'll enjoy this bot and obviously win ",15,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 200))  
				increaseSpacing(20)
				DrawText("your games.",15,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 200))  
				increaseSpacing(60)
				DrawText("Please check the changelog for updates",15,width - boxWidth + 10,y + spacing,4294967280)  
				increaseSpacing(20)
				DrawText("If something doesn't work tell me.",15,width - boxWidth + 10,y + spacing,RGBA(0, 255, 0, 200))  
			end
        
    end
	function setButtonText(text,color)
		buttonText = text
		buttonColor = color
	end
	function increaseSpacing(integer)
		spacing = spacing + integer
	end
	function DrawRectangle(x, y, width, height, color)
		DrawLine(x, y + (height/2), x + width, y + (height/2), height, color)
	end
	
	--Other functions--
	function PushMinionsTowards(capturePoint) --Pushes the minion wave (if any) towards the capture point (which you're outnumbered to)
	if escape_to then return end --If escape_to
	end
	function GetBase()
	--Information about base, points, etc.
	if myTEAM == TEAM_BLUE then

		heroBase = {x =	514.287109375  ,z = 4149.9916992188	}
		enemyBase = {x = 13311.96484375 ,z = 4161.232421875	}
		enemyMidDefendPoint = {x = 11157, z = 7762}
		enemyBotDefendPoint = {x = 9396, z = 2762}
	else
		heroBase = {x = 13311.96484375 ,z = 4161.232421875	}
		enemyBase = {x =	514.287109375  ,z = 4149.9916992188	}
		enemyMidDefendPoint = {x = 2802, z = 7660}
		enemyBotDefendPoint = {x = 4623, z = 2633}
	end
	end
	function enemyCountAtTower(tower,distance) --Enemy count at a tower, within a certain distance
		if not tower then return 0 end
		local count = 0
		if myHero.health/myHero.maxHealth < 0.2 then return 5 end
		for _,enemy in ipairs(enemies) do
			if enemy and not enemy.dead and enemy.visible and GetDistanceSqr(tower,enemy) <= distance^2 then
				count = count + 1
			end
		
		end
		return count
	end
	function getNearestAllyTower()
	if not (midLeftid and topid and midRightid and botLeftid and botRightid) then return end
		local nearest = nil
		local name_tower = ""
		local ally_towers = {}
		--Pinpoint locations
		if midLeft == 1 	then table.insert(ally_towers,{id = midLeftid,	 name_tower = "Middle Left"}) 	end
		if top == 1 		then table.insert(ally_towers,{id = topid,		 name_tower = "Top"}) 			end
		if midRight == 1 	then table.insert(ally_towers,{id = midRightid,	 name_tower = "Middle Right"})  end
		if botLeft == 1 	then table.insert(ally_towers,{id = botLeftid,	 name_tower = "Bottom Left"}) 	end
		if botRight == 1 	then table.insert(ally_towers,{id = botRightid,	 name_tower = "Bottom Right"}) 	end
		
		
		for i,tower in ipairs(ally_towers) do
		if tower then
			if tower.id and name_tower and not (enemyCountAtTower(tower.id,1200) > 2) then
				if not nearest then 
				nearest = tower.id 
				name_tower = tower.name_tower
				end
				local a = GetDistanceSqr(tower.id)
				local b = GetDistanceSqr(nearest)
					if a<b then
					nearest = tower.id
					name_tower = tower.name_tower
					end
			
				end
			end
		end
		
		return nearest,name_tower
		
	
	end
	function enemiesNearMe()
		return enemiesNearTarget(myHero)
	end
	
	function enemiesNearTarget(target,distance)
		local distinceToTarget = (distance or 1150)
		local count = 0
		for _,enemy in ipairs(enemies) do
			if not enemy.dead and DistanceBetweenLOE(target,enemy,distinceToTarget * (3/2)) then
			count = count + 1 
			end
		end
		return count
	end
	function balance_check() --Will help nearby allies if they can be balanced and they are currently engaged in battle
		-- 0 means balanced. x<0 means more enemies. x>0 means more allies.
		if not ts.target and myHero.health/myHero.maxHealth >= 0.5 then  --Only help if you can (hp wise)
			local safe_count = 0
			local distinceToTarget = 1150
			
			for _,ally_main in ipairs(allies) do
				if not ally_main.dead and not ally_main.isMe and DistanceBetweenLOE(myHero,ally_main,distinceToTarget*2.5) and GetDistance(myHero,ally_main) > 400 then
				
					--Ally count
					for _,ally in ipairs(allies) do
						if ally and ally.visible and not ally.dead and DistanceBetweenLOE(ally_main,ally,distinceToTarget) then
						safe_count = safe_count + 1 
						end
					end
					for _,enemy in ipairs(enemies) do
						if enemy and enemy.visible and not enemy.dead and DistanceBetweenLOE(ally_main,enemy,distinceToTarget * (3/2)) and (enemy.health/enemy.maxHealth) > enemyHealthRatio then
						safe_count = safe_count - 1 
						end
					end
				
				
					
					if safe_count == -1 or (safe_count == 0 and ally_main.health/ally_main.maxHealth < 0.4) then heroMove(ally_main.x,ally_main.z) state = "Helping out [".. ally_main.charName .. "]" return true end
					--To avoid switching between 2 nearby allies (will choose the first one that comes up)
				end
			end
			
		end
		return false
	end
	function safe_AOE_check() --checks the balance around YOU.
		-- 0 means balanced. x<0 means more enemies. x>0 means more allies.
		if ts.target then 
		local safe_count = 1 --Starts with you
		local distinceToTarget = 1150
		if DistanceBetweenLOE(ts.target,distinceToTarget) then
			--Ally count
			for _,ally in ipairs(allies) do
				if not ally.dead and (DistanceBetweenLOE(ts.target,ally, distinceToTarget) or DistanceBetweenLOE(myHero,ally, distinceToTarget)) then
				safe_count = safe_count + 1 
				end
				if ally.isMe then
				--PrintChat("YOU")
				end
			end
			for _,enemy in ipairs(enemies) do
				if not enemy.dead and DistanceBetweenLOE(ts.target,enemy,distinceToTarget * (5/6)) and (enemy.health/enemy.maxHealth) > enemyHealthRatio then
				safe_count = safe_count - 1 
				end
			end
		end
		
		return safe_count
		end
	end
	
	function SmartFight()
		local balance = safe_AOE_check()
		if balance < (-1)*currentBalance then return "retreat" end
	
		--If hero under tower and state = battle then
		if state == "battle" then
			if myHero.health/myHero.maxHealth > 0.7 and myHero.health > ts.target.health then --if can dive anyways
				return "battle"
			elseif myHero.health > ts.target.health and ts.target.health < 250 then
				return "battle"
			else
				local tower,state = getClosestTowerState()
				if tower ~= nil and state == 3 then 
					--Enemy tower
					if  DistanceBetweenLOE(ts.target ,tower, 750) then
						if ts.target ~= nil and DistanceBetweenLOE(ts.target ,myHero, 750) then
						
							--If ally closer to enemy then skip this nonsense
								if balance > 0 then return "battle" end
							--If balance is 0 and at tower..
								if myHero.range < 400 and DistanceBetweenLOE(ts.target,tower,750) then
								--Backoff (till target is outside tower)
								heroMove(makeDistance(myHero,tower,2300))
								return "positioning"
								elseif myHero.range > 400 and DistanceBetweenLOE(myHero,tower, 750) then
								heroMove(makeDistance(myHero,tower,2300))
								return "positioning"
								end
								
							if autoGarrison ~= nil then CastSpell(autoGarrison) end
							return "battle"
						else
							--From declaration above
							if balance == 0 then
							CaptureBase(tower) return "capping"
							end
						end
					else
					return "battle"
					end
				
				else
					return "battle" --Continue fighting (tower is neutral/ours/notClose)
				end
			end	
			return "battle"
			
		end
		

	end
	function battle_attack()
		--Items
		UseItems(ts.target)
		
		--Combo
		_ENV[action]()
		--myHero:Attack(ts.target)
	end
	function isDistancedFromEnemy(distance)
		for _,enemy in ipairs(enemies) do
			if GetDistanceSqr(enemy) <= distance^2 then
				return false
			end
		end
		return true
	end
    function smartEscape()
        local target = getClosestEnemy()
        local prev = 80000
        local main
        if not escape_points then print("no escape") end
        for _, ep in ipairs(escape_points) do
            if ep then
                local p = {x = ep[1],z=ep[2]}
                local d2p = GetDistance(p)
                local e2p = GetDistance(target,p)
                if e2p > d2p and e2p > 1000 then 
                    if not main then main = p end
                    if d2p < prev then
                        main = p
                        prev = d2p
                    end
                end
            end
        end
            if GetDistance(target) > 1200 then 
                if prev <= 50 then recall_delay = 0 end
                
                recall() return 
            end
            if main and prev > 50 then
                state = "moving to safe location"
                heroMove(main.x,main.z)
                recall_delay = os.clock() + 4
            elseif main then
                recall_delay = 0
                
                recall()
            else --Couldnt find a point since target was closer to all the points...
                heroMove(heroBase.x,heroBase.z)
            end
        
        
        
    end
    function recall()
        if not recall_delay then recall_delay = 0 end
        if recall_delay < os.clock() then
            state = "recalling"
            if GetDistance(heroBase) >= (myHero.ms * recallTime + 475) then
                if myHero.casting == 1 then isCastingDelay = 1000 + GetTickCount() end
                if stealthKey ~= nil and myHero.casting ~= 1 and GetTickCount() > (isCastingDelay or 0) then Cast(stealthKey[1],stealthKey[2]) end
                CastSpell(RECALL)
                recall_delay = os.clock() + 4.5
            elseif GetDistance(heroBase) < 300 then
                heroMove(myHero.x,myHero.z)
            else
                heroMove(heroBase.x,heroBase.z)
            end
        
            
        end
    end
	function battle_retreat()
        if escape_to and (myHero.health / myHero.maxHealth) > 0.3 then
            if DistanceBetweenLOE(escape_to,ts.target, 700) and DistanceBetweenLOE(escape_to, 1000) then
                battle_attack()
                state = "fighting under turret"
                return
            elseif not DistanceBetweenLOE(escape_to, 1000) then
                if isDistancedFromEnemy(800) then 
                farmMinions() return 
                else
                heroMove(CloserToMe(heroBase.x,heroBase.z,escape_to.x,escape_to.z)) --ALLY CAPTURE POINT or base whichever is closer
                end
            elseif DistanceBetweenLOE(escape_to, 1000) then
                
                heroMove(makeDistance(escape_to,ts.target,2000)) 
                
            end
        else
            --NEW RETREAT LOGIC
            smartEscape()
            --OLD RETREAT LOGIC
            --heroMove(CloserToMe(heroBase.x,heroBase.z,6920,6800)) --CENTER OF MAP or base whichever is closer
        end
	end
	--Generic function that calls the combo functions/retreat
	function Battle()
		--Calls for the action to be performed (e.g RyzeCombo())
		if ts.target ~= nil and math.sqrt((ts.target.x-enemyBase.x)^2+(ts.target.z-enemyBase.z)^2)>2100 then
			if myHero.health/myHero.maxHealth < 0.6 and myHero.health/myHero.maxHealth > recallThreshold and ts.target.health > 300 then
				state = "retreat"
				if goNearestRune(1000) == "rune" then return end
			end
				--If under that %hp AND their hp is significantly greater
				if myHero.health / myHero.maxHealth < runAway and ts.target.health >= myHero.health + 300 then
					
					state = "retreat"
					--PAIN CHANGE
					recall_delay = os.clock() + 5 --Time to keep running until recalling (run away time)
					battle_retreat()
					return
				else
					--[[ FIGHT ]]--
					state = "battle"
					if invulnTime and invulnTime > GetTickCount() then
						battle_attack()
					else
						local fight_action = SmartFight() 
						if fight_action == "battle" then
						battle_attack()
						return
						elseif fight_action == "retreat" then
						state = "fall back"
						battle_retreat()
					end
					return
					
					end
				end

		else
			state = "enemyBase"
			if myHero.z > 5171 and math.sqrt((myHero.x-enemyMidDefendPoint.x)^2+(myHero.z-enemyMidDefendPoint.z)^2)>1000 then SmartMove(enemyMidDefendPoint.x, enemyMidDefendPoint.z) return
			elseif myHero.z < 5171 and math.sqrt((myHero.x-enemyBotDefendPoint.x)^2+(myHero.z-enemyBotDefendPoint.z)^2)>1000 then SmartMove(enemyBotDefendPoint.x, enemyBotDefendPoint.z) return
			end
		end
	
	
	end
	function myMid()
		return my_mid
	end
	function myBot()
		return my_bot
	end
	function getSecretRole()
		if not (c_top and myMid() and myBot()) then return end
		local description
		math.randomseed(os.time())
		local index	= math.random(0,5)
		if index == 0 or index == 1 then defending = my_top description= "Defender of TOP" end
		if index == 2 or index == 3 then defending = myMid() description= "Defender of MID" end
		if index == 5 then defending = myBot() description= "Defender of BOT" end
	
	
		if defending then 
			return description
		else
			return
		end
	end
	
	function getAttackSpeed() -- Returns our heros attack speed
		return myHero.attackSpeed/(1/.625)
	end
	
	function getSpeedShrines()
		speedShrines = {}
		for i = 0, objManager.maxObjects do
			local obj = objManager:getObject(i)
			--Speed shrines
			if obj and obj.name == "OdinSpeedShrine" then
				table.insert(speedShrines,obj)
			end
					

		end
	end
	
	function farmMinions()
		if not killMinions then return false end
		for i,a_minion in ipairs(minionTable) do
		if a_minion and minionIsValid(a_minion) and a_minion.health > 0 then
			if DistanceBetweenLOE(myHero,a_minion, 800) and a_minion.visible then
				state = "clearing minions"
				if useSpells then
				--INSERT METHOD FOR CLEARING MINIONS USING SPELLS HERE (future)
                for _, spell in pairs(useSpells) do
				Cast(spell[1],spell[2],a_minion,nil,spell[3])
                myHero:Attack(a_minion)
                end
                return true
				else
				myHero:Attack(a_minion) return true
				end
				
			end
		else table.remove(minionTable, i) i = i - 1 end
		end
	end
	
	function getNearestShrine()
		local badShrine = {x=0,z=0}
		if not (#speedShrines == 3) then return badShrine end
			local nearest = nil
			local name = "Location:"	
			for i,shrine in ipairs(speedShrines) do
			if shrine then
			
				if i == 1 then 
					nearest = shrine
					name = "Location: (" ..tostring(shrine.x) ..", ".. tostring(shrine.z) .. ")"
				end
				local a = GetDistanceSqr(shrine)
				local b = GetDistanceSqr(nearest)
				if a<b then
					nearest = shrine
					name = "Location: (" ..tostring(shrine.x) ..", ".. tostring(shrine.z) .. ")"
				end
			
				end
			
			end
			
			return nearest,name
	end

	function buffManagement()
		--Speed shrine
		hasSpeedBuff = false
		for i = 1, myHero.buffCount, 1 do
			local buff = myHero:getBuff(i)
			if buff and buff.name and buff.valid then
				if buff.name == "odinspeedshrinebuff" then
					hasSpeedBuff = true
				end
			end
		end
	
	end
	
	function SmartMove(x,z)
		--Checks if time taken to go from point a to b is faster than recalling then moving to point b.
		--Calculate distance from base - b + (ms*recallTime), a - b
		--Moves to smaller value
		local baseDistance = math.sqrt((x-heroBase.x)^2+(z-heroBase.z)^2) + (myHero.ms * recallTime)
		local heroDistance = math.sqrt((x-myHero.x)^2+(z-myHero.z)^2)
		local shrine = getNearestShrine()
		local heroShrineDistance = math.sqrt((x-shrine.x)^2+(z-shrine.z)^2) + math.sqrt((shrine.x-myHero.x)^2+(shrine.z-myHero.z)^2) 
		if heroShrineDistance > heroDistance then
		
		if (heroDistance/myHero.ms) > 10 then
			speedRatio = 10
		else
			speedRatio = (heroDistance/myHero.ms)
		end
		heroShrineDistance = heroShrineDistance  - (myHero.ms * 0.28 * speedRatio) --Minus 30% from speedboost (10 second duration) (only when you're further away)
		
		end
	
		if baseDistance > heroDistance or baseDistance > heroShrineDistance then
			state = "moving"
			
			if heroDistance < heroShrineDistance or hasSpeedBuff then
				if captureCount() >= 2 and farmMinions() then return else
					heroMove(makeDistance(myHero,{x=x + math.random(-50,50),z=z+ math.random(-50,50)},200)) return 
				end
			else
			--Small distance to myHero and small distance to destination (added) and divided to find midpoint
			local distanceFromShrine = 300
			local shrine = {x=shrine.x,z=shrine.z}
			local dx_shrine,dz_shrine = makeDistance({x=x,z=z},shrine,distanceFromShrine)
			local hx_shrine,hz_shrine = makeDistance({x=myHero.x,z=myHero.z},shrine,distanceFromShrine)
			local x_shrine,z_shrine = (dx_shrine+hx_shrine)/2,(dz_shrine+hz_shrine)/2
			heroMove(x_shrine,z_shrine) return
			end
		else
			--If no runes are near check minions
			for i,object in ipairs(minionTable) do
				if minionIsValid(object) then
					--Minion is close
					if DistanceBetweenLOE(myHero,object, 900) then
						heroMove(CloserToMe(heroBase.x,heroBase.z,6920,7200))
						--Runs to base/center whichever is closer
						state = "moving"
						return "moving"
					end
				else table.remove(minionTable, i) i = i - 1
				end
			end
			
			if toBase < 1000 then heroMove(heroBase.x,heroBase.z) return end
			
			return smartRecall()
				
		end
	end
	function Cast(key,spellType,x,z,range)
        if not myHero:CanUseSpell(key) == READY then return end
        if range and not (range and GetDistance(x,myHero) < range) then return end
        if x and z then CastSpell(key,x,z) return end
        if x then 
            if spellType == 4 then
            CastSpell(key,x) return 
            elseif spellType == 5 then
            CastSpell(key,x.x,x.z) return 
            end
        end
        if spellType == 1 then CastSpell(key)
		elseif spellType == 2 then CastSpell(key,myHero.x,myHero.z)
		elseif spellType == 3 then CastSpell(key,myHero)
        elseif spellType == 4 then CastSpell(key,ts.target)
        elseif spellType == 5 then CastSpell(key,ts.target.x,ts.target.z)
        end
	end
    
	function smartRecall()
		if os.clock() > recall_delay then
			state = "recalling"
			if GetDistance(heroBase) >= (myHero.ms * recallTime + 475) then
            
				if stealthKey ~= nil and myHero.casting ~= 1 then Cast(stealthKey[1],stealthKey[2]) end
				CastSpell(RECALL)
				recall_delay = os.clock() + 4.5
			elseif GetDistance(heroBase) < 300 then
				heroMove(myHero.x,myHero.z)
			else
				heroMove(heroBase.x,heroBase.z)
			end
			
		end
		return "recall"
	end
	
	function makeDistance(A,B,distance)
        --Moves in the direction of point A from B  (B->A)
        local x,z = A.x,A.z
        local x1,z1 = B.x,B.z
        local gradient = (z - z1)/( x - x1)
        if x > x1 then
            return   ((math.cos(math.atan(gradient))) * distance) + x1,((math.sin(math.atan(gradient))) * distance) + z1
        else
            return  -((math.cos(math.atan(gradient))) * distance) + x1,-((math.sin(math.atan(gradient))) * distance) + z1
        end
	end
	
	function MinionCollision(posStart, posEnd, spellWidth)
		assert(VectorType(posStart) and VectorType(posEnd) and type(spellWidth) == "number", "GetMinionCollision: wrong argument types (<Vector>, <Vector>, <number> expected)")
		local distance = GetDistanceSqr(posStart, posEnd)
		for i,object in ipairs(minionTable) do
			if minionIsValid(object) and not object.dead then
				if object and object.team ~= myTEAM and object.type == "AI_Minion" and not object.dead and object.visible then
					if GetDistanceSqr(object, posStart) < distance and GetDistanceSqr(object, posEnd) < distance then
						local closestPoint = VectorPointProjectionOnLine(posStart, posEnd, object)
						if DistanceBetweenLOE(closestPoint, object, spellWidth / 2) then return true end
					end
				end
			end
			return false
		end
	end

	function entropyRun(x,y,entropy)
		heroMove(x + math.random(-entropy,entropy), y + math.random(-entropy,entropy))
	end
	function IsPoisoned(target)
    local delay = math.max(GetDistance(target), 700)/1800 + 0.125
	for i = 1, target.buffCount do
		local tBuff = target:getBuff(i)
		if BuffIsValid(tBuff) and (tBuff.name == "cassiopeianoxiousblastpoison" 
            or tBuff.name == "cassiopeiamiasmapoison"
            or tBuff.name == "toxicshotparticle"
            or tBuff.name == "bantamtraptarget"
            or tBuff.name == "poisontrailtarget"
            or tBuff.name == "deadlyvenom") and tBuff.endT - delay - GetGameTimer() > 0 then
			return true
		end
	end 
    return false
end	
	function StartPosition()

	if myTEAM == TEAM_BLUE then
		heroMove(630,4427) 
	else
		heroMove(13200,4388)
	end

	end

	function getClosestTowerState()
		local nearest = nil
		local condition = 1
		--Pinpoint locations
		if math.sqrt((myHero.x-midLeftid.x)^2+(myHero.z-midLeftid.z)^2) < 1600 then
			condition = midLeft
			nearest = midLeftid

		elseif math.sqrt((myHero.x-topid.x)^2+(myHero.z-topid.z)^2) < 1600 then
			condition = top
			nearest = topid

		elseif math.sqrt((myHero.x-midRightid.x)^2+(myHero.z-midRightid.z)^2) < 1600 then
			condition = midRight
			nearest = midRightid

		elseif math.sqrt((myHero.x-botLeftid.x)^2+(myHero.z-botLeftid.z)^2) < 1600 then
			condition = botLeft
			nearest = botLeftid

		elseif math.sqrt((myHero.x-botRightid.x)^2+(myHero.z-botRightid.z)^2) < 1600 then
			condition = botRight
			nearest = botRightid

		end
		if nearest == nil then return nil,nil,nil end
		if condition ~= 1 then
		return nearest, condition
		else 
		return nil,1,nearest
		end
	end
	function ItemBuyCheck()
        return myHero.gold > goldThreshold --or (items[buyIndex] and IsRecipePurchasable(items[buyIndex]))
    end
	function ManaCheck()
		return (myHero.mana/myHero.maxMana < 0.5 and myHero.maxMana > 200) and not (myHero.charName == "Aatrox" or myHero.charName == "Mordekaiser")
	end
	function HealthCheck()
		return myHero.health / myHero.maxHealth < recallThreshold
	end
	--Checks if it's best to recall or to head to nearest rune (hp)
	function recallCheck() --Runes only added to table once they have been seen
    	if myHero.health > 100 then --Cap if health is above here (avoiding minion temp)
			local tower = getClosestTowerState()
			if tower ~= nil then
				if enemiesNearTarget(tower,2000) == 0 then
					--IF NO ENEMY IS AT THE CAPTURE POINT THEN CAP
					CaptureBase(tower) return "capping"
				end
			end
		end
		--Check if getting rune is even worth staying
		if ((myHero.health + (0.07 * myHero.maxHealth)) / myHero.maxHealth) < recallThreshold or ItemBuyCheck()  then
		--Don't go for runes (pointless)
		else
			--Heal up buddie
			for i,object in ipairs(runeTable) do
				if runeIsValid(object) then
					if DistanceBetweenLOE(myHero,object, 2000) and (HealthCheck() or ManaCheck()) then
						heroMove(object.x,object.z)
						if DistanceBetweenLOE(myHero,object, 400) and not object.visible then
							table.remove(runeTable, i) i = i - 1
						end
						return "rune"
					end
				else table.remove(runeTable, i) i = i - 1
				end
				
			end
			
		end
			--If no runes are near check minions
			for i,object in ipairs(minionTable) do
				if minionIsValid(object) then
					--Minion is close
					if DistanceBetweenLOE(myHero,object, 700) and (HealthCheck() or ItemBuyCheck()) then
						heroMove(CloserToMe(heroBase.x,heroBase.z,6920,7200))
						--Runs to base/center whichever is closer
						return "moving"
					end
				else table.remove(minionTable, i) i = i - 1
				end
			end
			
			if (HealthCheck() or ItemBuyCheck()) then
				if toBase < 1500 then heroMove(heroBase.x,heroBase.z) return "moving" end
				
				for _,enemy in ipairs(enemies) do
					if enemy and not enemy.dead and not enemy.visible and DistanceBetweenLOE(myHero,enemy,1500) then
					--HideLogicHere
					state = "hiding from enemy"
					return "moving"
					end
				end
				
				return smartRecall()
			end
	end
	
	function UseItems(target)
        for _,item in pairs(activeItems) do
			item.slot = GetInventorySlotItem(item.id)
			if item.slot ~= nil then
				if DistanceBetweenLOE(target, item.range) then
					CastSpell(item.slot, target)
				end
			end
		end
	end
	
	--Goes to nearest hp relic
	function goNearestRune(distance)
		for i,object in ipairs(runeTable) do
			if runeIsValid(object) then
		
				if DistanceBetweenLOE(myHero,object, distance) then
					
				heroMove(object.x,object.z)
					if DistanceBetweenLOE(myHero,object, 400) and not object.visible then
						table.remove(runeTable, i) i = i - 1
					end
					
					return "rune"
					
		        end
			else table.remove(runeTable, i) i = i - 1
			end
			
		end
		return "none"
	end
	
	function CloserToMe(x1,z1,x2,z2)
		local point = {}
		point.x,point.z = x1,z1
		distance = GetDistanceSqr(myHero,point)
		point.x,point.z = x2,z2
		distance2 = GetDistanceSqr(myHero,point)
		
		if distance2 > distance then
		return x1,z1
		else
		return x2,z2
		end
	end
	
	--Load emotion defaults
	function setEmotions()
		--SendEmotion(0)
		--Emotions
		dance = 0 
		taunt = 1 
		laugh = 2
		joke  = 3
		emotion = 0
		refreshTime = 1600 -- was 4000
		lastTime = 0
		count = 0
	end
	--Emotion state changer
	function emotional()
		if count > 10 and emotion == dance then
			emotion = taunt
			count = 0
		elseif count > 10 and emotion == taunt then
			emotion = laugh
			count = 0
		elseif count > 10 and emotion == laugh then
			emotion = joke
			count = 0
		elseif count > 5 and emotion == joke then
			emotion = dance
			count = 0
		end
		
		SendEmotion(emotion)
		count = count + 1
	end
	function isAAReset(n)
		if n == "Ricochet" or n == "VayneTumble" or n == "VayneTumbleUltAttack" or n == "DariusNoxianTacticsONH" or n == "BlindingDart" or n == "EzrealMysticShot" or n == "EzrealEssenceFlux" or n == "QuinnQMissile" or n == "QuinnE" or n == "MissFortuneRicochetShot" or n == "GravesClusterShot" or n == "Overload" or n == "SpellFlux" or n == "RunePrison" or n == "PhosphorusBomb" or n == "MissileBarrageMissile" or n == "MissileBarrageMissile2" or n == "MissFortuneRicochetShot" or n == "JudicatorReckoning" or n == "JavelinToss" or n == "Takedown" or n == "Swipe"
		then return true else return false end
	end
	--[[ SPELL PROCESSOR ]]--
	function OnProcessSpell(object,spell)
        if object and turretIsValid(object) and DistanceBetweenLOE(myHero,spell.endPos,150) then towerFocused = true aggroedTower = object end
        if STOP_ACTIVITY then return end
        --ORBWALKING
        if object and object.isMe then
            if isAAReset(spell.name) then castAttack,nextAttack = 0,0 end
            if spell.name:lower():find("attack") then
                castAttack = GetTickCount() + (spell.windUpTime) * 1000 - GetLatency() / 2 + 10
                nextAttack = GetTickCount() + (spell.animationTime) * 1000 - GetLatency() / 2 + 10
            end
        end
        
        
        --Move to where the enemy is capturing 
        if not object or not spell then return end --bugsplatted at next line
        if object.team ~= myTEAM and spell.name == "OdinCaptureChannel" and GetDistance(myHero,object) < 2000 and ts.target == nil and (myHero.health / myHero.maxHealth > recallThreshold) then
        SmartMove(object.x,object.z)
        end

        --For a few champions
        if object.isMe and (spell.name:find("BasicAttack" or "CritAttack") ~= nil) then
            swing = 1
            lastBasicAttack = os.clock()
            
        if not QState or QState == false and myHero.charName == "Ashe" then CastSpell(_Q) QState = true end	
        end
        --Irelia
        if myHero.charName == "Irelia" then
            if object.isMe then
                if spellName == "IreliaHitenStyle" then
                    lasthiten = os.clock()
                    hitendmg = spell.level*15
                end
            end
        end
        --Vayne
        if myHero.charName == "Vayne" then
            if object.isMe and (spell.name == "VayneTumbleAttack" or spell.name == "VayneTumbleUltAttack") then
            lastAttack = 0
            return
            end
            if object.isMe and ((spell.name:find("BasicAttack" or "CritAttack") ~= nil) or spell.name:find("VayneCritAttack") or spell.name:find("UltAttack"))  then
            lastAttack = GetTickCount()
            end
        end
        
        --Ashe
        if spell.name == "frostarrow" and myHero.charName == "Ashe" then CastSpell(_Q) QState = false end
        if QState == false and spell.name == "FrostShot" and myHero.charName == "Ashe" then CastSpell(_Q) QState = true end
        
        --Katarina
        if object.isMe and spell.name == "KatarinaQ" then timeq = GetTickCount() end
	end
	
	--[[ OBJECT VALID CHECKER ]]--
	function minionIsValid(object)
        if GetGame().map.index == 8 then --DOMINION MINIONS
            return object ~= nil and object.valid and (string.find(object.name,"_Minion") or string.find(object.name,"Superminion")) and string.find(object.type,"AI_Minion") and not object.dead and object.team ~= myTEAM
        elseif GetGame().map.index == 12 then --ARAM MINIONS
            return object ~= nil and object.valid and (string.find(object.name,"Minion_T"..tostring(enemyTEAM))) and not object.dead
        end
        
	end
    function turretIsValid(object)
		return object ~= nil and object.valid and (string.find(object.charName,"Turret")) and object.team ~= myTEAM 
	end
    function inhibIsValid(object)
        return object ~= nil and object.valid and (string.find(object.name,"Barrack")) and object.team ~= myTEAM 
    end
	function runeIsValid(object)
	return object and object.valid and string.find(object.name,"odin_heal_rune") ~= nil
	end
	
	--[[ OBJECT FUNCTIONS ]]--
	function OnCreateObj(obj)
		if STOP_ACTIVITY then return end
		--Minions
		if minionIsValid(obj) then table.insert(minionTable, obj) end
		if runeIsValid(obj) then table.insert(runeTable, obj) end

		--CAPTURE POINTS
		if (topid and midLeftid and midRightid and botRightid and botLeftid) then 
            if obj ~= nil then
                ---CAPTURE POINT GREEN
                if obj.name == "OdinNeutralGuardian_Green.troy" then
                    if (math.sqrt((obj.x-topid.x)^2+(obj.z-topid.z)^2))<1000 then top = 1 end
                    if (math.sqrt((obj.x-midLeftid.x)^2+(obj.z-midLeftid.z)^2))<1000 then midLeft = 1 end
                    if (math.sqrt((obj.x-midRightid.x)^2+(obj.z-midRightid.z)^2))<1000 then midRight = 1 end
                    if (math.sqrt((obj.x-botLeftid.x)^2+(obj.z-botLeftid.z)^2))<1000 then botLeft = 1 end
                    if (math.sqrt((obj.x-botRightid.x)^2+(obj.z-botRightid.z)^2))<1000 then botRight = 1 end
                end
                ---CAPTURE POINT RED
                if obj.name == "OdinNeutralGuardian_Red.troy" then
                    if (math.sqrt((obj.x-topid.x)^2+(obj.z-topid.z)^2))<1000 then top = 3 end
                    if (math.sqrt((obj.x-midLeftid.x)^2+(obj.z-midLeftid.z)^2))<1000 then midLeft = 3 end
                    if (math.sqrt((obj.x-midRightid.x)^2+(obj.z-midRightid.z)^2))<1000 then  midRight = 3 end
                    if (math.sqrt((obj.x-botLeftid.x)^2+(obj.z-botLeftid.z)^2))<1000 then botLeft = 3 end
                    if (math.sqrt((obj.x-botRightid.x)^2+(obj.z-botRightid.z)^2))<1000 then  botRight = 3 end
                end


                ---CAPTURE POINT NEUTRAL
                if obj.name == "OdinNeutralGuardian_Stone.troy" then
                if (math.sqrt((obj.x-topid.x)^2+(obj.z-topid.z)^2))<1000 then top = 2 end
                if (math.sqrt((obj.x-midLeftid.x)^2+(obj.z-midLeftid.z)^2))<1000 then midLeft = 2 end
                if (math.sqrt((obj.x-midRightid.x)^2+(obj.z-midRightid.z)^2))<1000 then midRight = 2 end
                if (math.sqrt((obj.x-botLeftid.x)^2+(obj.z-botLeftid.z)^2))<1000 then botLeft = 2 end
                if (math.sqrt((obj.x-botRightid.x)^2+(obj.z-botRightid.z)^2))<1000 then botRight = 2 end
                end
            end
		end
		---Detects start of game object creation
		if obj ~= nil and obj.name == "SpawnBeacon.troy" then StartPosition()  BuyItems() end
		if obj ~= nil and obj.name == "Odin_stairfx_blue.troy" then PrintChat("Game started, goodluck.") GetIds() BuyItems() end

		--Champion Specials
		if extra ~= nil and obj~= nil then
		championObjects("Create",obj)
		end
		
		updateCapturePoints()
	end

	function OnDeleteObj(obj)
		if extra ~= nil and obj ~= nil then
		championObjects("Delete",obj)
		end
	end
	

	--[[ SUMMONER SPELL FUNCTIONS ]]--
	function Summoners()
		if not summonersSet then
            
            if myHero:GetSpellData(SUMMONER_1).name == "SummonerRevive" then
				autoRevive = SUMMONER_1
			elseif myHero:GetSpellData(SUMMONER_2).name == "SummonerRevive" then
				autoRevive = SUMMONER_2
			end
			if myHero:GetSpellData(SUMMONER_1).name == "SummonerPromoteSR" then
				autoPromote = SUMMONER_1
			elseif myHero:GetSpellData(SUMMONER_2).name == "SummonerPromoteSR" then
				autoPromote = SUMMONER_2
			end
			if myHero:GetSpellData(SUMMONER_1).name == "SummonerOdinGarrison" then
				autoGarrison = SUMMONER_1
			elseif myHero:GetSpellData(SUMMONER_2).name == "SummonerOdinGarrison" then
				autoGarrison = SUMMONER_2
			end
			if myHero:GetSpellData(SUMMONER_1).name == "SummonerBarrier" then
				autoBarrier = SUMMONER_1
			elseif myHero:GetSpellData(SUMMONER_2).name == "SummonerBarrier" then
				autoBarrier = SUMMONER_2
			end
			if myHero:GetSpellData(SUMMONER_1).name == "SummonerMana" then
				autoMana = SUMMONER_1
			elseif myHero:GetSpellData(SUMMONER_2).name == "SummonerMana" then
				autoMana = SUMMONER_2
			end
			if myHero:GetSpellData(SUMMONER_1).name == "SummonerBoost" then
				autoCleanse = SUMMONER_1
				cleanseList = {
				"stun", "Stun", "Fear", "taunt", "LuxLightBindingMis", "Wither", "SonaCrescendo", "RunePrison", 
				"DarkBindingMissile", "caitlynyordletrapdebuff", "EnchantedCrystalArrow", "CurseoftheSadMummy", "LuluWTwo", "fizzmarinerdoombomb"
				 }
			elseif myHero:GetSpellData(SUMMONER_2).name == "SummonerBoost" then
				autoCleanse = SUMMONER_2
				cleanseList = {
				"stun", "Stun", "Fear", "taunt", "LuxLightBindingMis", "Wither", "SonaCrescendo", "RunePrison", 
				"DarkBindingMissile", "caitlynyordletrapdebuff", "EnchantedCrystalArrow", "CurseoftheSadMummy", "LuluWTwo", "fizzmarinerdoombomb"
				 }
			end
			if myHero:GetSpellData(SUMMONER_1).name == "SummonerExhaust" then
				autoExhaust = SUMMONER_1
			elseif myHero:GetSpellData(SUMMONER_2).name == "SummonerExhaust" then
				autoExhaust = SUMMONER_2
			end
			if myHero:GetSpellData(SUMMONER_1).name == "SummonerHaste" then
				autoGhost = SUMMONER_1
			elseif myHero:GetSpellData(SUMMONER_2).name == "SummonerHaste" then
				autoGhost = SUMMONER_2
			end
			if myHero:GetSpellData(SUMMONER_1).name == "SummonerClairvoyance" then
				autoClair = SUMMONER_1
				clairSpots = {{5198,8686},{6944,8166}, {8704,8766}, {6957,5509}, {5121,5984}, {8737,5836}, {6948,3838}}
			elseif myHero:GetSpellData(SUMMONER_2).name == "SummonerClairvoyance" then
				autoClair = SUMMONER_2
				clairSpots = {{5198,8686},{6944,8166}, {8704,8766}, {6957,5509}, {5121,5984}, {8737,5836}, {6948,3838}}
			end
			if myHero:GetSpellData(SUMMONER_1).name == "SummonerDot" then
				autoIgnite = SUMMONER_1
			elseif myHero:GetSpellData(SUMMONER_2).name == "SummonerDot" then
				autoIgnite = SUMMONER_2
			end
			if myHero:GetSpellData(SUMMONER_1).name == "SummonerHeal" then
				autoSummonerHeal = SUMMONER_1
			elseif myHero:GetSpellData(SUMMONER_2).name == "SummonerHeal" then
				autoSummonerHeal = SUMMONER_2
			end
			summonersSet = true
		end
		--Actions
		if autoBarrier ~= nil and myHero.health/myHero.maxHealth < 0.4 then 
			CastSpell(autoBarrier)
		end
		if myHero.mana < (myHero.maxMana * 0.6) and autoMana ~= nil then
			CastSpell(autoMana)
		end
		if autoPromote ~= nil then CastSpell(autoPromote) end
		if ts.target ~= nil and autoCleanse ~= nil then
			for k = 1, myHero.buffCount, 1 do
				for j = 1, #cleanseList, 1 do
					if myHero:getBuff(k) == cleanseList[j] then
						CastSpell(autoCleanse)
					end
				end
			end
		end
		if autoGhost ~= nil and myHero.canMove and ts.target ~= nil then
			--If running away from target
			if myHero.health / myHero.maxHealth < runAway then
			CastSpell(autoGhost)		
			elseif 
			myHero.health > ts.target.health and myHero.ms < ts.target.ms then
			CastSpell(autoGhost)	
			end
		end
		--If near clair point then clair
		if autoClair ~= nil then
			for j = 1, heroManager.iCount do
               	local target = heroManager:GetHero(j)
				if target ~= nil and target.visible == true and target.team ~= myTEAM and target.dead == false and target.health < target.maxHealth * 0.5 then
		
					for i, spot in pairs(clairSpots) do
					if math.sqrt((target.x-spot[1])^2+(target.z-spot[2])^2) < 1000 then					
					CastSpell(autoClair,spot[1],spot[2])	
					end
					end
				end
			end
		end
		if myHero.health/myHero.maxHealth < 0.4 and autoSummonerHeal ~= nil then
			CastSpell(autoSummonerHeal)
		end
		if ts.target ~= nil and autoExhaust ~= nil and DistanceBetweenLOE(ts.target,myHero, 550) then
		
			CastSpell(autoExhaust,ts.target)
		end
		if ts.target ~= nil and autoIgnite ~= nil then
			autoIgnitedamage = 50 + 20*myHero.level
			if ts.target.health <= autoIgnitedamage then CastSpell(autoIgnite,ts.target) end
		end
	end

	function checkCaptureState()
		for i = 0, objManager.maxObjects do
		local obj = objManager:getObject(i)
			--If reloaded
			---CAPTURE POINT GREEN
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Green.troy" and (math.sqrt((obj.x-topid.x)^2+(obj.z-topid.z)^2))<1500 then top = 1 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Green.troy" and (math.sqrt((obj.x-midLeftid.x)^2+(obj.z-midLeftid.z)^2))<1500 then midLeft = 1 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Green.troy" and (math.sqrt((obj.x-midRightid.x)^2+(obj.z-midRightid.z)^2))<1500 then midRight = 1 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Green.troy" and (math.sqrt((obj.x-botLeftid.x)^2+(obj.z-botLeftid.z)^2))<1500 then botLeft = 1 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Green.troy" and (math.sqrt((obj.x-botRightid.x)^2+(obj.z-botRightid.z)^2))<1500 then botRight = 1 end



			---CAPTURE POINT RED
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Red.troy" and (math.sqrt((obj.x-topid.x)^2+(obj.z-topid.z)^2))<1500 then top = 3 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Red.troy" and (math.sqrt((obj.x-midLeftid.x)^2+(obj.z-midLeftid.z)^2))<1500 then midLeft = 3 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Red.troy" and (math.sqrt((obj.x-midRightid.x)^2+(obj.z-midRightid.z)^2))<1500 then  midRight = 3 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Red.troy" and (math.sqrt((obj.x-botLeftid.x)^2+(obj.z-botLeftid.z)^2))<1500 then botLeft = 3 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Red.troy" and (math.sqrt((obj.x-botRightid.x)^2+(obj.z-botRightid.z)^2))<1500 then  botRight = 3 end



			---CAPTURE POINT NEUTRAL
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Stone.troy" and (math.sqrt((obj.x-topid.x)^2+(obj.z-topid.z)^2))<1500 then top = 2 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Stone.troy" and (math.sqrt((obj.x-midLeftid.x)^2+(obj.z-midLeftid.z)^2))<1500 then midLeft = 2 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Stone.troy" and (math.sqrt((obj.x-midRightid.x)^2+(obj.z-midRightid.z)^2))<1500 then midRight = 2 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Stone.troy" and (math.sqrt((obj.x-botLeftid.x)^2+(obj.z-botLeftid.z)^2))<1500 then botLeft = 2 end
			if obj ~= nil and obj.name == "OdinNeutralGuardian_Stone.troy" and (math.sqrt((obj.x-botRightid.x)^2+(obj.z-botRightid.z)^2))<1500 then botRight = 2 end

		end
	--NEUTRAL IF NOT FOUND
	if not top then top = 2 end
	if not midLeft then midLeft = 2 end
	if not midRight then midRight = 2 end
	if not botLeft then botLeft = 2 end
	if not botRight then botRight = 2 end
	
	
	
	end
	--Identify Capture Point Objects (and their current state)
	function GetIds()
		if failTime == 0 then return end
		local countTower = 0
		for i = 0, objManager.maxObjects do
		local obj = objManager:getObject(i)
				--Pinpoint locations
				if obj ~= nil and math.sqrt((obj.x-2497)^2+(obj.z-7808)^2) < 1000 and obj.name == "OdinNeutralGuardian" then
					midLeftid = obj
					countTower = countTower + 1
					--PrintChat(obj.networkID)
				end
				if obj ~= nil and math.sqrt((obj.x-6951)^2+(obj.z-10978)^2) < 1000 and obj.name == "OdinNeutralGuardian" then
					topid = obj
					countTower = countTower + 1
					--PrintChat(obj.networkID)
				end
				if obj ~= nil and math.sqrt((obj.x-11381.59)^2+(obj.z-7619.35)^2) < 1000 and obj.name == "OdinNeutralGuardian" then
					midRightid = obj
					countTower = countTower + 1
					--PrintChat(obj.networkID)
				end
				if obj ~= nil and math.sqrt((obj.x-4308)^2+(obj.z-2454)^2) < 1000 and obj.name == "OdinNeutralGuardian" then
					botLeftid = obj
					countTower = countTower + 1
					--PrintChat(obj.networkID)
				end
				if obj ~= nil and math.sqrt((obj.x-9511)^2+(obj.z-2492)^2) < 1000 and obj.name == "OdinNeutralGuardian" then
					botRightid = obj
					countTower = countTower + 1
					--PrintChat(obj.networkID)
				end
				
		end
		
		if countTower == 5 then
			PrintChat("TowerID Successfully Retrieved.")
			output("TowerID Successfully Retrieved.")
			failTime = 0
			checkCaptureState()
		else
			PrintChat("TowerID Failed... ")
			output("TowerID Failed... restarting game client")
			--RunCmdCommand('taskkill /IM "League Of Legends.exe"')
			
			
		end
		
	end
	function BuyItem2(id) --TEMP FIX FOR BOL
        p = CLoLPacket(0x81)
        p:EncodeF(myHero.networkID)
        p:Encode2(id)
        p:Encode1(0)
        p:Encode1(0)
        p.dwArg1 = 1
        p.dwArg2 = 0	
        SendPacket(p)
    end
	--[[ ITEM FUNCTIONS ]]--
	function BuyItems()
        
        local buyPot = true
        if getFreeSlots() <= 2 then buyPot = false 
            SellItemId(2003) 
        end
		if GetTickCount() - itemTickCount > 800 then
			itemTickCount = GetTickCount()
           
			
			if buyIndex > #items then
				goldThreshold = 9999999 --Finished buying items. No reason to go back because of this.
			else
                if InShop() and (itemStacks(2003) or 0) < 2 and buyIndex < #items - 2 and getFreeSlots() >2 then 
                    if buyPot then
                        --BuyItem(2003) 
                    end
                end
				if (buyIndex == #items - 1 or buyIndex == #items) and use_item_queue then
					goldThreshold = 3100 --Last item should cost around here (worst case) until the item array gets sorted.
				end
				if items[buyIndex] == nil then buyIndex = buyIndex + 1 end
				if GetInventorySlotItem(items[buyIndex]) ~= nil then
					buyIndex = buyIndex + 1
				else
                    BuyItem2(items[buyIndex])
					
				end
			end	
		
		end
		
		
	end

	function getFreeSlots()
		local count = 0
        if itemStacks(2003) ~= nil then count = count + 1 end
		if GetInventorySlotIsEmpty(ITEM_1) then count = count + 1 end
		if GetInventorySlotIsEmpty(ITEM_2) then count = count + 1 end
		if GetInventorySlotIsEmpty(ITEM_3) then count = count + 1 end
		if GetInventorySlotIsEmpty(ITEM_4) then count = count + 1 end
		if GetInventorySlotIsEmpty(ITEM_5) then count = count + 1 end
		if GetInventorySlotIsEmpty(ITEM_6) then count = count + 1 end
		return count
	end
	
	function LoadItemTable()
	itemTable = {
        --Consumables
        {name = "Frozen Mallet", id = 3022, requires = {"Ruby Crystal","Giant's Belt","Pickaxe"}},
        {name = "Banner of Command", id = 3060, requires = {"Fiendish Codex","Blasting Wand"}},
        {name = "Augment: Power", id = 3196, requires = {"Hex Core"}},
        {name = "Faerie Charm", id = 1004, requires = {}},
        {name = "Mana Potion", id = 2004, requires = {}},
        {name = "Mercury's Treads", id = 3111, requires = {"Boots of Speed","Null-Magic Mantle"}},
        {name = "Frostfang", id = 3098, requires = {"Spellthief's Edge"}},
        {name = "Explorer's Ward", id = 2050, requires = {}},
        {name = "Zeke's Herald", id = 3050, requires = {"Kindlegem","Vampiric Scepter"}},
        {name = "Brawler's Gloves", id = 1051, requires = {}},
        {name = "Trinity Force", id = 3078, requires = {"Zeal","Sheen","Phage"}},
        {name = "Guardian Angel", id = 3026, requires = {"Negatron Cloak","Chain Vest"}},
        {name = "Randuin's Omen", id = 3143, requires = {"Giant's Belt","Warden's Mail"}},
        {name = "Blasting Wand", id = 1026, requires = {}},
        {name = "Quicksilver Sash", id = 3140, requires = {"Negatron Cloak"}},
        {name = "Enchantment: Captain", id = 3261, requires = {"Ninja Tabi"}},
        {name = "Moonflair Spellblade", id = 3170, requires = {"Seeker's Armguard","Negatron Cloak"}},
        {name = "Bonetooth Necklace", id = 3418, requires = {}},
        {name = "Enchantment: Distortion", id = 3253, requires = {"Berserker's Greaves"}},
        {name = "Chain Vest", id = 1031, requires = {}},
        {name = "Last Whisper", id = 3035, requires = {"Pickaxe","Long Sword"}},
        {name = "Crystalline Flask", id = 2041, requires = {}},
        {name = "Enchantment: Captain", id = 3256, requires = {"Sorcerer's Shoes"}},
        {name = "Giant's Belt", id = 1011, requires = {}},
        {name = "Liandry's Torment", id = 3151, requires = {"Haunting Guise","Amplifying Tome"}},
        {name = "Enchantment: Furor", id = 3262, requires = {"Ninja Tabi"}},
        {name = "Talisman of Ascension", id = 3069, requires = {"Nomad's Medallion","Forbidden Idol"}},
        {name = "Seraph's Embrace", id = 3040, requires = {"Archangel's Staff"}},
        {name = "Poro-Snax", id = 2052, requires = {}},
        {name = "Blackfire Torch", id = 3188, requires = {"Blasting Wand","Fiendish Codex"}},
        {name = "Bonetooth Necklace", id = 3422, requires = {"Bonetooth Necklace"}},
        {name = "Bonetooth Necklace", id = 3421, requires = {}},
        {name = "Bonetooth Necklace", id = 3420, requires = {}},
        {name = "Lich Bane", id = 3100, requires = {"Sheen","Aether Wisp"}},
        {name = "Ravenous Hydra", id = 3074, requires = {"Tiamat","Vampiric Scepter"}},
        {name = "Bonetooth Necklace", id = 3417, requires = {}},
        {name = "Muramana", id = 3042, requires = {"Manamune"}},
        {name = "Sword of the Divine", id = 3131, requires = {"Recurve Bow","Dagger"}},
        {name = "Warden's Mail", id = 3082, requires = {"Chain Vest"}},
        {name = "Entropy", id = 3184, requires = {"Phage","Pickaxe"}},
        {name = "Grez's Spectral Lantern", id = 3159, requires = {"Cloth Armor","Vampiric Scepter"}},
        {name = "Runaan's Hurricane", id = 3085, requires = {"Dagger","Recurve Bow","Dagger"}},
        {name = "Athene's Unholy Grail", id = 3174, requires = {"Fiendish Codex","Chalice of Harmony"}},
        {name = "Dagger", id = 1042, requires = {}},
        {name = "Iceborn Gauntlet", id = 3025, requires = {"Sheen","Glacial Shroud"}},
        {name = "Vampiric Scepter", id = 1053, requires = {"Long Sword"}},
        {name = "Boots of Speed", id = 1001, requires = {}},
        {name = "Abyssal Scepter", id = 3001, requires = {"Blasting Wand","Negatron Cloak"}},
        {name = "Prospector's Ring", id = 1063, requires = {}},
        {name = "Ninja Tabi", id = 3047, requires = {"Boots of Speed","Cloth Armor"}},
        {name = "Seraph's Embrace", id = 3048, requires = {"Archangel's Staff"}},
        {name = "Long Sword", id = 1036, requires = {}},
        {name = "Enchantment: Captain", id = 3271, requires = {"Boots of Mobility"}},
        {name = "Enchantment: Alacrity", id = 3279, requires = {"Ionian Boots of Lucidity"}},
        {name = "Feral Flare", id = 3160, requires = {"Wriggle's Lantern"}},
        {name = "Enchantment: Alacrity", id = 3284, requires = {"Boots of Swiftness"}},
        {name = "Void Staff", id = 3135, requires = {"Blasting Wand","Amplifying Tome"}},
        {name = "Hextech Revolver", id = 3145, requires = {"Amplifying Tome","Amplifying Tome"}},
        {name = "Sanguine Blade", id = 3181, requires = {"Pickaxe","Vampiric Scepter"}},
        {name = "Pickaxe", id = 1037, requires = {}},
        {name = "Enchantment: Distortion", id = 3268, requires = {"Mercury's Treads"}},
        {name = "Hexdrinker", id = 3155, requires = {"Long Sword","Null-Magic Mantle"}},
        {name = "Cloth Armor", id = 1029, requires = {}},
        {name = "Spectre's Cowl", id = 3211, requires = {"Ruby Crystal","Negatron Cloak"}},
        {name = "Rod of Ages", id = 3029, requires = {"Catalyst the Protector","Blasting Wand"}},
        {name = "Spellthief's Edge", id = 3303, requires = {}},
        {name = "Relic Shield", id = 3302, requires = {}},
        {name = "Archangel's Staff", id = 3007, requires = {"Tear of the Goddess","Blasting Wand"}},
        {name = "Enchantment: Furor", id = 3252, requires = {"Berserker's Greaves"}},
        {name = "Twin Shadows", id = 3290, requires = {"Fiendish Codex","Aether Wisp"}},
        {name = "Atma's Impaler", id = 3005, requires = {"Chain Vest","Avarice Blade"}},
        {name = "Prospector's Blade", id = 1062, requires = {}},
        {name = "Frost Queen's Claim", id = 3092, requires = {"Frostfang","Fiendish Codex"}},
        {name = "Thornmail", id = 3075, requires = {"Cloth Armor","Chain Vest"}},
        {name = "Enchantment: Furor", id = 3282, requires = {"Boots of Swiftness"}},
        {name = "Cloak of Agility", id = 1018, requires = {}},
        {name = "Rejuvenation Bead", id = 1006, requires = {}},
        {name = "Enchantment: Captain", id = 3281, requires = {"Boots of Swiftness"}},
        {name = "Berserker's Greaves", id = 3006, requires = {"Boots of Speed","Dagger"}},
        {name = "Enchantment: Distortion", id = 3278, requires = {"Ionian Boots of Lucidity"}},
        {name = "Chalice of Harmony", id = 3028, requires = {"Faerie Charm","Null-Magic Mantle","Faerie Charm"}},
        {name = "Enchantment: Furor", id = 3277, requires = {"Ionian Boots of Lucidity"}},
        {name = "Ruby Crystal", id = 1028, requires = {}},
        {name = "Dervish Blade", id = 3137, requires = {"Quicksilver Sash","Stinger"}},
        {name = "Enchantment: Distortion", id = 3283, requires = {"Boots of Swiftness"}},
        {name = "Wit's End", id = 3091, requires = {"Recurve Bow","Null-Magic Mantle","Dagger"}},
        {name = "Statikk Shiv", id = 3087, requires = {"Zeal","Avarice Blade"}},
        {name = "Sunfire Cape", id = 3068, requires = {"Chain Vest","Giant's Belt"}},
        {name = "Enchantment: Captain", id = 3266, requires = {"Mercury's Treads"}},
        {name = "Targon's Brace", id = 3097, requires = {"Relic Shield"}},
        {name = "Avarice Blade", id = 3093, requires = {"Brawler's Gloves"}},
        {name = "Doran's Shield", id = 1054, requires = {}},
        {name = "Morellonomicon", id = 3165, requires = {"Fiendish Codex","Forbidden Idol"}},
        {name = "Enchantment: Alacrity", id = 3264, requires = {"Ninja Tabi"}},
        {name = "Enchantment: Distortion", id = 3263, requires = {"Ninja Tabi"}},
        {name = "Enchantment: Furor", id = 3267, requires = {"Mercury's Treads"}},
        {name = "Enchantment: Alacrity", id = 3259, requires = {"Sorcerer's Shoes"}},
        {name = "Enchantment: Distortion", id = 3258, requires = {"Sorcerer's Shoes"}},
        {name = "Ionian Boots of Lucidity", id = 3158, requires = {"Boots of Speed"}},
        {name = "Phage", id = 3044, requires = {"Ruby Crystal","Long Sword"}},
        {name = "Enchantment: Furor", id = 3257, requires = {"Sorcerer's Shoes"}},
        {name = "Brutalizer", id = 3134, requires = {"Long Sword","Long Sword"}},
        {name = "Nomad's Medallion", id = 3096, requires = {"Ancient Coin"}},
        {name = "Rylai's Crystal Scepter", id = 3116, requires = {"Blasting Wand","Amplifying Tome","Giant's Belt"}},
        {name = "Zephyr", id = 3172, requires = {"Stinger","Pickaxe"}},
        {name = "Blade of the Ruined King", id = 3153, requires = {"Dagger","Bilgewater Cutlass","Dagger"}},
        {name = "Enchantment: Captain", id = 3251, requires = {"Berserker's Greaves"}},
        {name = "Mikael's Crucible", id = 3222, requires = {"Chalice of Harmony"}},
        {name = "Manamune", id = 3008, requires = {"Tear of the Goddess","Long Sword"}},
        {name = "Hex Core", id = 3200, requires = {}},
        {name = "Augment: Gravity", id = 3197, requires = {"Hex Core"}},
        {name = "Augment: Death", id = 3198, requires = {"Hex Core"}},
        {name = "Stinger", id = 3101, requires = {"Dagger","Dagger"}},
        {name = "Spirit Visage", id = 3065, requires = {"Spectre's Cowl","Kindlegem"}},
        {name = "Haunting Guise", id = 3136, requires = {"Ruby Crystal","Amplifying Tome"}},
        {name = "Seeker's Armguard", id = 3191, requires = {"Cloth Armor","Amplifying Tome","Cloth Armor"}},
        {name = "Boots of Mobility", id = 3117, requires = {"Boots of Speed"}},
        {name = "Locket of the Iron Solari", id = 3190, requires = {"Aegis of the Legion","Kindlegem"}},
        {name = "Hextech Sweeper", id = 3187, requires = {"Fiendish Codex","Kindlegem"}},
        {name = "Lightbringer", id = 3185, requires = {"Grez's Spectral Lantern","Ruby Crystal"}},
        {name = "Odyn's Veil", id = 3180, requires = {"Negatron Cloak","Catalyst the Protector"}},
        {name = "Bilgewater Cutlass", id = 3144, requires = {"Long Sword","Vampiric Scepter"}},
        {name = "Catalyst the Protector", id = 3010, requires = {"Ruby Crystal","Sapphire Crystal"}},
        {name = "Total Biscuit of Rejuvenation", id = 2010, requires = {}},
        {name = "Guinsoo's Rageblade", id = 3124, requires = {"Blasting Wand","Pickaxe"}},
        {name = "Amplifying Tome", id = 1052, requires = {}},
        {name = "Wooglet's Witchcap", id = 3090, requires = {"Seeker's Armguard","Blasting Wand","Amplifying Tome"}},
        {name = "Recurve Bow", id = 1043, requires = {}},
        {name = "Sorcerer's Shoes", id = 3020, requires = {"Boots of Speed"}},
        {name = "Enchantment: Furor", id = 3272, requires = {"Boots of Mobility"}},
        {name = "Boots of Swiftness", id = 3009, requires = {"Boots of Speed"}},
        {name = "Total Biscuit of Rejuvenation", id = 2009, requires = {}},
        {name = "Sheen", id = 3057, requires = {"Sapphire Crystal","Amplifying Tome"}},
        {name = "Sapphire Crystal", id = 1027, requires = {}},
        {name = "Negatron Cloak", id = 1057, requires = {}},
        {name = "Maw of Malmortius", id = 3156, requires = {"Hexdrinker","Pickaxe"}},
        {name = "Enchantment: Alacrity", id = 3254, requires = {"Berserker's Greaves"}},
        {name = "Will of the Ancients", id = 3152, requires = {"Hextech Revolver","Fiendish Codex"}},
        {name = "Hextech Gunblade", id = 3146, requires = {"Bilgewater Cutlass","Hextech Revolver"}},
        {name = "Executioner's Calling", id = 3123, requires = {"Avarice Blade","Long Sword"}},
        {name = "Enchantment: Captain", id = 3276, requires = {"Ionian Boots of Lucidity"}},
        {name = "Face of the Mountain", id = 3401, requires = {"Targon's Brace","Kindlegem"}},
        {name = "Null-Magic Mantle", id = 1033, requires = {}},
        {name = "Youmuu's Ghostblade", id = 3142, requires = {"Avarice Blade","Brutalizer"}},
        {name = "Wicked Hatchet", id = 3122, requires = {"Brawler's Gloves","Long Sword"}},
        {name = "Nashor's Tooth", id = 3115, requires = {"Stinger","Fiendish Codex"}},
        {name = "Forbidden Idol", id = 3114, requires = {"Faerie Charm","Faerie Charm"}},
        {name = "Frozen Heart", id = 3110, requires = {"Warden's Mail","Glacial Shroud"}},
        {name = "Aether Wisp", id = 3113, requires = {"Amplifying Tome"}},
        {name = "Fiendish Codex", id = 3108, requires = {"Amplifying Tome"}},
        {name = "Aegis of the Legion", id = 3105, requires = {"Ruby Crystal","Negatron Cloak","Rejuvenation Bead"}},
        {name = "Lord Van Damm's Pillager", id = 3104, requires = {"Wicked Hatchet","Pickaxe","Cloak of Agility"}},
        {name = "Enchantment: Alacrity", id = 3274, requires = {"Boots of Mobility"}},
        {name = "Enchantment: Distortion", id = 3273, requires = {"Boots of Mobility"}},
        {name = "Zeal", id = 3086, requires = {"Brawler's Gloves","Dagger"}},
        {name = "Tiamat", id = 3077, requires = {"Pickaxe","Long Sword","Rejuvenation Bead","Rejuvenation Bead"}},
        {name = "Bonetooth Necklace", id = 3419, requires = {}},
        {name = "Tear of the Goddess", id = 3073, requires = {"Sapphire Crystal","Faerie Charm"}},
        {name = "Health Potion", id = 2003, requires = {}},
        {name = "Phantom Dancer", id = 3046, requires = {"Cloak of Agility","Zeal","Dagger"}},
        {name = "Black Cleaver", id = 3071, requires = {"Brutalizer","Ruby Crystal"}},
        {name = "Enchantment: Alacrity", id = 3269, requires = {"Mercury's Treads"}},
        {name = "Kindlegem", id = 3067, requires = {"Ruby Crystal"}},
        {name = "Glacial Shroud", id = 3024, requires = {"Sapphire Crystal","Cloth Armor"}},
        {name = "Muramana", id = 3043, requires = {"Manamune"}},
        {name = "Ancient Coin", id = 3301, requires = {}},
        {name = "Oracle's Extract", id = 2047, requires = {}},

		}
	end
    function explode(d,s)
        if (d=='') then return false end
        local p,ar = 0,{}
        for st,sp in function() return string.find(s,d,p,true) end do
            table.insert(ar,tonumber(string.sub(s,p,st-1)))
            p = sp + 1
        end
        table.insert(ar,tonumber(string.sub(s,p)))
        return ar
    end
    --Logically figures out skill order
    function getSkillOrder(str)
        --4  Characters [eg. QWER]
        --18 Characters [whole skill order]
        --3  Characters [eg. QWE - R is automatically leveled at 6,11,16]
        local s1,s2,s3,s4,s5 = nil,nil,nil,nil,#str
        if s5 == 18 then 
            local full
            str:gsub(".", function(c)
                if not full then full = c else
                full = full .."," ..c end
            end)
            return explode(",",full:gsub('Q',1):gsub('W',2):gsub('E',3):gsub('R',4)) end
        if s5 == 3 then s4 = "R" end
        if s5 == 4 or s5 == 3 then else return {} end 
        for i = 1, #str do
            local c = str:sub(i,i)
            if not s1 then s1 = c else
            if s1 and not s2 then s2 = c else
            if s2 and not s3 then s3 = c else
            if s3 and not s4 then s4 = c else
            end
            end
            end
            end
        end
        
        local template = "a,b,c,a,a,d,b,a,a,b,d,b,b,c,c,d,c,c"
        template = template:gsub('a',s1):gsub('b',s2):gsub('c',s3):gsub('d',s4)
        --print(template)
        template = template:gsub('Q',1):gsub('W',2):gsub('E',3):gsub('R',4)
        --print(template)
        return explode(",",template)
    end
    --Logically figures out item buy order
    function getItemOrder(str)
        return string.split(str,",")
    end
    
    function readCfgBasedOn(key)
        --returns string after '=' based on champ & key
        local champion_data={}
        local n=0
        local config = ReadIni(dominionCfg_PATH)
        if not config[key] then return "",true end
        for k,v in pairs(config[key]) do
            n=n+1
            champion_data[n]={k:gsub(' ',''):gsub("'",''):gsub("Wukong","MonkeyKing"):gsub("Dr.Mundo","DrMundo"),k}
        end
        
        table.sort(champion_data[1])
        for i,champion in ipairs(champion_data) do
        if string.lower(myHero.charName) == string.lower(tostring(champion[1])) then
            return config[key][champion[2]]
        end
        end
    end
    --From the config file.
    function LoadSkillOrder()
        if Load_Items == true then
            local s,err = readCfgBasedOn("SKILLS")
            skill_text = s
            if not err then display_custom_skills = true else return end
            s = s:gsub('{',''):gsub('}',''):gsub(' "',''):gsub('" ',''):gsub('"',''):gsub(',','')
            local order = getSkillOrder(s)
            if #order == 18 then levelSequence = getSkillOrder(s) else display_custom_skills = false end
        end
    end
	--From the config file.
     function LoadItems()
        if Load_Items == true then
            local s,err = readCfgBasedOn("ITEMS")
            items_text = s
            if not err then display_custom_items = true else return end
            s = s:gsub('{',''):gsub('}',''):gsub(' "',''):gsub('" ',''):gsub('"','')
            local items_load = getItemOrder(s)
            
            --Implementing
            for i=1,#items_load do 
                if tonumber(items_load[i]) ~= nil then
                    items_load[i] = tonumber(items_load[i])
                end
            end    
            
            if #items_load >=6 then
                items = items_load
                item_state = "custom"
                display_custom_items = false --off by default
            else
                item_state = "default"
                display_custom_items = false
            end
            
        end
    end
    --From the config file.
	function LoadItemss()
		--Load items
        if Load_Items == true then
			local champ_item_found = false
            local champ_skill_found = false
			local config = ReadIni(dominionCfg_PATH)
            
			local champion_names_from_file={}
            local champion_skills_ff = {}
			local n=0
			local items_load = nil	
			for k,v in pairs(config.ITEMS) do
				n=n+1
				champion_names_from_file[n]={k:gsub(' ',''):gsub("'",''):gsub("Wukong","MonkeyKing"):gsub("Dr.Mundo","DrMundo"),k}
			end
            n=0
            
            
			table.sort(champion_names_from_file[1])
            
			for i,champion in ipairs(champion_names_from_file) do
				if string.lower(myHero.charName) == string.lower(tostring(champion[1])) then
				champ_item_found = true
				if not config.ITEMS[champion[2]] then item_state = "default" return end
				item_from_config_string = config.ITEMS[champion[2]]:gsub('{',''):gsub('}',''):gsub(' "',''):gsub('" ',''):gsub('"','')
				if not item_from_config_string then item_state = "default" return end
				items_load = string.split(item_from_config_string,",")
				break
				end
			end
            
            
			if champ_item_found then
				for i=1,#items_load do 
					if tonumber(items_load[i]) ~= nil then
						items_load[i] = tonumber(items_load[i])
					end
					
				end
				if #items_load >=6 then
				items = items_load
				item_state = "custom"
				else
				item_state = "default"
				end
			else
			item_state = "default"
			end
		else
			item_state = "default"
		end
		
	end
	function decideRoles()
		if GetInGameTimer() > 75 then
			c_role = "[bottom]"
			for _,ally in ipairs(allies) do
			
				if ally and (ally.isMe == 0 or not ally.isMe) and ally.z < myHero.z then
				--Champion role
				c_role = "[normal]"

				end			
			end
			setCaptureOrder()
			function decideRoles() end
		end
	end	
	function getVirtualSlot(itemID)
		for i = 1, #virtualSlots do
			if virtualSlots[i] == itemID then virtualSlots[i] = nil return i end
		end
		return nil
	end

	function createItemQueue()

		local loopCount = 1
		local itemsToBuy = {}
		local stringItems = ""
		--	local itemSet = "<ITEMS>"
		local freeslots = getFreeSlots()
		virtualSlots = {}
		--So when running this it knows where you are up to.
		virtualSlots[1] = myHero:getInventorySlot(ITEM_1)
		virtualSlots[2] = myHero:getInventorySlot(ITEM_2)
		virtualSlots[3] = myHero:getInventorySlot(ITEM_3)
		virtualSlots[4] = myHero:getInventorySlot(ITEM_4)
		virtualSlots[5] = myHero:getInventorySlot(ITEM_5)
		virtualSlots[6] = myHero:getInventorySlot(ITEM_6)

	
		for itemIndex = 1,#items do
		if freeslots <= 0 then break end
			for _,itemList in ipairs(itemTable) do
				if itemList.id == items[itemIndex] or string.lower(itemList.name) == string.lower(items[itemIndex]) then
					if not getVirtualSlot(itemList.id) then
					if freeslots <= 0 then break end
					freeslots = freeslots - 1
						if itemList.requires ~= nil then
							if #itemList.requires < freeslots then loopCount = freeslots elseif freeslots <= -1 then break else loopCount = #itemList.requires end
								for j = 1,loopCount do
									--Tier2
									for k=1,#itemTable do
										if itemTable[k].name == itemList.requires[j] then
											if not getVirtualSlot(itemTable[k].id) then
												freeslots = freeslots - 1 
												if itemTable[k].requires ~= nil then
													if #itemTable[k].requires < freeslots then loopCount = freeslots elseif freeslots <= -1 then break else loopCount = #itemTable[k].requires end
														for a = 1,loopCount do
															for l=1,#itemTable do
																if itemTable[l].name == itemTable[k].requires[a] then
																	if not getVirtualSlot(itemTable[l].id) then
																	freeslots = freeslots - 1
																	table.insert(itemsToBuy,itemTable[l].id) 
																	end
																break	--Breaks the "all the items loop"
																end
															end
														end
												end
											freeslots = freeslots + #itemTable[k].requires
											
											table.insert(itemsToBuy,itemTable[k].id)
											end
											break --Breaks the "all the items loop"
										end
									end
								end
							end
					freeslots = freeslots + #itemList.requires
					
					table.insert(itemsToBuy,itemList.id)
					end
					break --Breaks the "all the items loop"
				end --Match the items loop
				
			end --All the items loop
		end
		if #items == 6 then
			use_item_queue = true
			items = nil
			items = {}
			for i=1,#itemsToBuy do
				if i == #itemsToBuy then
						for _,item in ipairs(itemTable) do
							if item.id == itemsToBuy[i] then 
								for _,req in ipairs(itemTable) do
								if req.name == item.requires[1] then
								items[i] = req.id
								reqFound = true
								end
								end
							end --Prereq for the last item
						end
						if reqFound then
						items[i+1] = itemsToBuy[i] --Last item
						else
						items[i+1] = itemsToBuy[i] --Last item
						end
				else
					items[i] = itemsToBuy[i]
				end
			end			
			for i=1,#items do
			--if stringItems == "" then stringItems = items[i] else stringItems = stringItems .. ", " .. items[i] end
			if stringItems == "" then stringItems = itemName(items[i]) else stringItems = stringItems .. ", " .. itemName(items[i]) end
			end
			if stringItems ~= "" then
			PrintChat("Build order: " ..tostring(stringItems))
			end
		
		end
		
	end
	function itemName(itemCode)
		for _, item in ipairs(itemTable) do
			if item.id == itemCode then return item.name end
		end
		
		
		return "[Unknown item: "..tostring(itemCode).."]"
	end
	function OnBugSplat()
        output("BUGSPLATTED.")
        output(debug.traceback())
        local file = io.open(SCRIPT_PATH.."KevinBot/bugsplat", "w")
        file:write("bugsplatted - closing and restarting everything")
        file:close()
	end
	
	function output(message) --
		local file = io.open(CHATLOG_PATH, "a")
		if file then
			file:write(tostring(message .. "\n"))
			file:close()
		end
	end
	function getClosestEnemy()
        local closestEnemy
            for _, thisEnemy in pairs(enemies) do
                if not thisEnemy.dead then
                    if not closestEnemy then closestEnemy = thisEnemy
                    elseif GetDistance(thisEnemy) < GetDistance(closestEnemy) then closestEnemy = thisEnemy end
                end
            end
        return closestEnemy
    end
	function makeFile() --Put in onLoad() to make a new file every time you press f9 twice
		local file = io.open(CHATLOG_PATH, "w")
		if file then
			file:write(tostring(""))
			file:close()
		end
	end
	function checkDefensiveNeeds() --Checks if an ally tower needs defending (enemy count with closest allies(prioritize)
		if defending and defending[1] then
			if defending[1] <= 2 and enemyCountAtTower(defending[2],2000) >= 1 then
				state = "Defending personal point"
				return defending[2]
			end
		end
        
        local mid_point = {getCapturePoint("mid")}
        local top_point = {getCapturePoint("top")}
        if mid_point and top_point then
            if mid_point[1] == 1 and top_point[1] ~= 1 and enemyCountAtTower(mid_point[2],2000) >= 1 then
                --Goto mid since it needs defending
                state = "Defending key point (mid)"
                return mid_point[2]
            elseif mid_point[1] == 1 and top_point[1] == 1 and enemyCountAtTower(top_point[2],2000) >= 1 then
                --Goto top since it needs defending
                state = "Defending key point (top)"
                return top_point[2]
            end
        end
		
	end
	--[[ PACKET SEND/RECEIVE ]]--
	function CaptureBase(BaseID,optionalState)
	
		--If there is a tower nearby prioritize that (1500 units)
		local tower = getClosestTowerState()
		if tower ~= nil then
			if tower ~= BaseID then
				BaseID = tower
			end
		end
		
	
		--Recalls if capture point can be reached faster this way
		if (not DistanceBetweenLOE(myHero,BaseID,1500) or optionalState) and not tower then 
					
				SmartMove(BaseID.x,BaseID.z)
				if optionalState then state = optionalState end	
		else
		
		if tower then
		sendCapturePacket(BaseID)
		else
		state = "Defending point"
		
		end
		
	
		end
	end
	function sendCapturePacket(capturePoint)
		capTime = GetTickCount()
		p = CLoLPacket(capturePacket)
		p:EncodeF(myHero.networkID)
		p:EncodeF(capturePoint.networkID)
		p.dwArg1 = 1
		p.dwArg2 = 0
		state = "capping"
		SendPacket(p)
	
	end
	function SendEmotion(EmotionID)
		p = CLoLPacket(emotionPacket)
		p:EncodeF(myHero.networkID)
		p:Encode1(EmotionID)
		p.dwArg1 = 1
		p.dwArg2 = 0

		SendPacket(p)
	end
	function emptyFile(path)
        local file = io.open(path,"w")
        file:write("")
        file:close()
    end
    function WriteFile(text,path)
        local file = io.open(path,"w")
        file:write(text)
        file:close()
    end
    function Quit() --Exit game after delay
		--os.execute('@echo off && timeout '..seconds..' && taskkill /IM "League Of Legends.exe"')
		--os.execute('start "close" "'..SCRIPT_PATH..'/KevinBot/closure.exe')
		
		SecondsToQuit = SecondsToQuit - 1
		if SecondsToQuit <= 0 then
            WriteFile(myHero.name,SCRIPT_PATH..'/KevinBot/summonerName')
            emptyFile(SCRIPT_PATH..'/KevinBot/game_end')
            RunCmdCommand('taskkill /IM "League Of Legends.exe"')
            
            closeText = "Close command sent!"
		else
		return SecondsToQuit
		end
		
	end

	function OnRecvPacket(p)
		lastRecvPacketTime = os.clock()
		
		if p.header == 0xC3  then 
		--	PrintChat("Picked up health pack!")
		end
		
		if p.header == gameOverPacket then
			--gameOver = true --So you don't start doing stuff after the game ends
			--SendChat("gg.")
			--PrintChat(tostring(p.header))
			if 1==1 then return end
		--	timeNow = os.clock()
		--	Quit()
		end
		
	end
	function empty()
	
	end
	--[[ ON LOAD ]]--
	function OnLoad()
		if dom_load then return end
        
        if GetGame().map.index == 8 then
            gameLogic = dominionLogic
        elseif GetGame().map.index == 12 then
            gameLogic = aramLogic
        end
        --Load dominion variables
		dConfig()
        GetBase() --MUST LOAD AFTER dCONFIG
		enemies = GetEnemyHeroes()
		allies = GetAllyHeroes()
        initializeCombos()
        --Chat logs
		makeFile()
		output("Player name: "..myHero.name .." || Played as " ..myHero.charName)
		output("----------------------------------------------------------------")
		--Summoners
		summonersSet = false
		--Items array. Will fill up on champ loadout.--
		items = {}
		activeItems = {
			BRK = {id=3153, range = 500, slot = nil},
			BWC = {id=3144, range = 400, slot = nil},
			HGB = {id=3146, range = 400, slot = nil},
			YGB = {id=3142, range = myHero.range + 50, slot = nil},
			STD = {id=3131, range = myHero.range + 50, slot = nil},
			RSH = {id=3074, range = 350, slot = nil},
			TMT = {id=3077, range = 350, slot = nil},
			BFT = {id=3188, range = 600, slot = nil},--DFG replacement
            DFG = {id=3128, range = 600, slot = nil},
			EXE = {id=3123, range = 350, slot = nil},
			RAN = {id=3143, range = 350, slot = nil},
			ODN = {id=3180, range = 525, slot = nil},
			LIS = {id=3190, range = 600, slot = nil},
			HTS = {id=3187, range = 1000, slot = nil},
			MAR = {id=3042, range = 350, slot = nil}
		}
		--Getting and setting champion--

		LoadChampionDetails()
		escapeDelay = 0
		--Items
        
		LoadItems() --Config file
		LoadItemTable()
		createItemQueue()
		
		--Capture timeout--
		capTime = 0
		
		--Level Spells--
		itemTickCount = GetTickCount()
		
		--Thresholds (global?)
		enemyHealthRatio= 0.3 	--Will only count enemies greater than this % when it comes to deciding whether one is outnumbered.
		runAway			= 0.27 	--Run away from ts.target if you are at this %hp or lower
		recallThreshold = 0.4 	--%hp when to back
		goldThreshold 	= 2000 	--The gold amount before recalling
		manaThreshold   = 0.5	--Threshold for mana
		--Recall time
		recallTime 		= 4.5
		
		--Spell stuff
		lastBasicAttack = 0
		
		
		setEmotions()
		getReplyList()
		--Loading all objects--
		for i = 0, objManager.maxObjects, 1 do
        local object = objManager:GetObject(i)
        --Minions--
		if minionIsValid(object) then table.insert(minionTable, object) end
		--Health Relics--
		if runeIsValid(object) then table.insert(runeTable, object) end
		end
		
		--Game state--
		gameOver = false
		--Load complete--
        
		bot_game = isBotGame()
		
		buffEnds = 0
		--Display Message--
		
		
		GetIds()
		
		getSpeedShrines()
		
		PrintChat("Profile Verified.. ")
        local protag = "STANDARD"
        if Load_Items then protag = "PRO" end
		PrintChat("KBot ["..mapName.."]_"..isBeta().." _ "..protag.." _ Loaded <" .. myHero.charName .. ">")
		dom_load = true
		--Getting and setting IDs--
		output("KevinBot Loaded Successfully")
		PriorityOnLoad()
		LoadSkillOrder() --Config file
        
        
	end
	function isBeta()
		if _G.beta then
			return "BETA"
		else
			return "PUBLIC"
		end
	end
	function updateCapturePoints()
		if c_role then
			c_mid =  {getCapturePoint(captureOrder[1])}
			c_top = {getCapturePoint(captureOrder[2])}
			c_bot =  {getCapturePoint(captureOrder[3])}
			c_tmid = {getCapturePoint(captureOrder[4])}
			c_tbot = {getCapturePoint(captureOrder[5])}
		end
		
		my_top = {getCapturePoint("top")}
		my_mid = {getCapturePoint("mid")}
		my_bot = {getCapturePoint("bot")}
	end
	--Set standard names for capture points (mid, their mid [t_mid]) etc.
	function setCaptureOrder()
	if #enemies < 5 then c_role = "custom game [normal]" captureOrder = {"bot","mid","top","t-mid","t-bot"} end
    if c_role == "[normal]" then
		captureOrder = {"mid","top","bot","t-mid","t-bot"}
	elseif c_role == "[bottom]" then
		captureOrder = {"bot","mid","t-bot","top","t-mid"}
	end
	end
    function getSpellSlot(value)
        if value == 1 then return SPELL_1
        elseif value == 2 then return SPELL_2
        elseif value == 3 then return SPELL_3
        elseif value == 4 then return SPELL_4
        end
    end
    function levelupSkills(sequence)
        if not abilityLevel then abilityLevel = 0 end
        if myHero.level > abilityLevel then
            abilityLevel=abilityLevel+1
            LevelSpell(getSpellSlot(sequence[abilityLevel]))
        end
    end
	--Get capture points relative to teams
	function getCapturePoint(name)
		if myTEAM == TEAM_BLUE then
		if name == "mid" then return midLeft,midLeftid end
		if name == "top" then return top,topid end
		if name == "bot" then return botLeft,botLeftid end
		if name == "t-mid" then return midRight,midRightid end
		if name == "t-bot" then return botRight,botRightid end
		elseif myTEAM == TEAM_RED then
		if name == "mid" then return midRight,midRightid end
		if name == "top" then return top,topid end
		if name == "bot" then return botRight,botRightid end
		if name == "t-mid" then return midLeft,midLeftid end
		if name == "t-bot" then return botLeft,botLeftid end
		end
	end
	function enemyVisible()
		for _,enemy in ipairs(enemies) do
			if enemy and enemy.visible then return true end
		end
		return false
	end
	function SetMuramana()
        if ts.target then
            MuramanaOn()
        else
            MuramanaOff()
        end
    end
    function UseHPPotion()
        if (HPTick or 0) < GetTickCount() and not InFountain() then 
            for i=1, 6, 1 do
                if myHero:getInventorySlot(_G["ITEM_"..i]) == 2003 then
                    CastSpell(_G["ITEM_"..i])
                    HPTick = GetTickCount() + 15000
                    break
                end
            end
        end
    end
    function SellItemId(id)
        for i=1, 6, 1 do
            if myHero:getInventorySlot(_G["ITEM_"..i]) == id then
                SellItem((_G["ITEM_"..i]))
            end
        end
    end
    function itemStacks(id)
        for i=1, 6, 1 do
            if myHero:getInventorySlot(_G["ITEM_"..i]) == id then
                return (myHero:getItem(_G["ITEM_"..i]).stacks)
            end
        end
    end
    
    --ARAM functions
    function GetARAMObjects()
        local t = {}
        local inh = {}
        for i = 0, objManager.maxObjects, 1 do
        local object = objManager:GetObject(i)
        --Minions--
		if turretIsValid(object) then table.insert(t, object) end
        if inhibIsValid(object) then table.insert(inh,object) end
        if object and object.team ~= myTEAM and (object.name == "HQ_T1" or object.name == "HQ_T2") then nexusObject = object end
		end
        return t,inh
    end
    function ARAMOBJECTIVES()
        if not turrets then turrets,inhibs = GetARAMObjects() end
        --Attack inhibs if attackable, attack turrets if inhib is not attackable, attack nexus if all turrets are down
        for i, inhi in ipairs(inhibs) do
            if #turrets <= 3 and inhi and inhi.valid and inhi.health > 0 then myHero:Attack(inhi) return end
        end
        for i,turr in ipairs(turrets) do
            if #turrets > 1 and turr and turr.valid and not turr.dead then
                myHero:Attack(turr) 
            else 
                table.remove(turrets, i) i = i - 1
            end
        end
        if #turrets <= 1 and nexusObject and nexusObject.valid then myHero:Attack(nexusObject) end
    end
    
    function enemyMinionsAround()
        for _,m in pairs(minionTable) do
            if m and not m.dead and DistanceBetweenLOE(myHero,m,800) then
                myHero:Attack(m)
                return true
            end
        end
        
    end
    --GAME TYPES (ARAM,DOMINION,SR)
    function aramLogic()
        if towerFocused then
            if DistanceBetweenLOE(myHero,aggroedTower,900) then 
                myHero:MoveTo(0,0) return
            else
                towerFocused = false
            end
        end
        
        if ts.target then
        --Attack him or something
            return
        end
        if enemyMinionsAround() then
        --Attack them duh.
            return
        end
        
        --Otherwise push objectives
        ARAMOBJECTIVES()
    end
    
    function dominionLogic()
        if GetInGameTimer() < 10 then return end
		if GetInGameTimer() > 1500 then currentBalance = 2 --25mins (will engage 1v3)
		elseif GetInGameTimer() > 600 then currentBalance = 1 --12mins (will engage 1v2)
		--else currentBalance = 5
		end
		if not myHero or (myHero and not myHero.valid and not myHero.level) then PrintChat("INVALIDMYHERO") myHero = GetMyHero() return else --Solves invalid object after a lag.
		if levelSequence then levelupSkills(levelSequence) end
		end
		decideRoles()
		updateCapturePoints()
		buffManagement()
		--Escape/Safe locations
		if not myHero.dead and GetTickCount() > escapeDelay then
		escape_to,escape_name = getNearestAllyTower()	
		escapeDelay = GetTickCount() + 500
		end
		if gameOver then return end
		if GetInGameTimer() < BuyStartTime then return end
		--- calculate distance to keyzones
		
		toBase = math.sqrt((myHero.x-heroBase.x)^2+(myHero.z-heroBase.z)^2) --Left base
		toTop = math.sqrt((myHero.x-6920)^2+(myHero.z-11149)^2)
		if ts.mode then ts.mode = TARGET_CLOSEST end
		ts:update()
		SetMuramana()
		if autoRevive and myHero and myHero.dead then CastSpell(autoRevive) end
		if toBase > 1500 and state ~= "recalling" then Summoners() end
		
		if myHero.health > 0 and myHero.health + 200 < myHero.maxHealth then UseHPPotion() end
		if toBase < 300 and ItemBuyCheck() then BuyItems() return end
		if (myHero.health / myHero.maxHealth) <= 0.75 and toBase < 1200 then BuyItems() return end 
		if myHero.dead or toBase < 1000 then BuyItems() end
		if myHero.health == 0 then 
			--Dead passives, actions and what not
			if deadActions then deadActions() end
			if myHero.dead then 
				state = "dead" 
				if not gotTimeDeath then
				deathTimer = GetTickCount() + myHero.deathTimer
				gotTimeDeath = true
				end
				
			end
			return
		end
		if GetInGameTimer() < 79 then return end --No calculating/routing before the gates open
		
		if not defense_roles or (defense_roles and roleChange < GetTickCount()) then 
            defense_roles = getSecretRole() 
            roleChange = GetTickCount() + 300000
        end
		
		if championPassives then
		if GetTickCount() > capTime + 500 then
			if championPassives() ~= nil then return end
		end
		end
		
		if balance_check() then return end
		
		if ts.target ~= nil and not ts.target.dead then 
			Battle() return
		end
		if ts.target == nil or (ts.target and not ts.target.visible) then
		

		if (HealthCheck() or ManaCheck() or ItemBuyCheck()) and ts.target == nil and toBase > 500 then
			local check = recallCheck()
			if check == "rune" or check == "recall" or check == "moving" or check == "capping" then return end
		end

        if myHero.health/myHero.maxHealth < 0.7 then
			--If tower can be capped then cap the tower
			local tower = getClosestTowerState()
				--myHero.health > 100 < 100
				if tower ~= nil and enemiesNearTarget(tower,2000) == 0 then
					CaptureBase(tower) return "capping"
				end
				if goNearestRune(1300) == "rune" then return end
		end
		--Checks point of defense for any enemies and moves to defend it if there are enemies there
		if myHero.casting ~= 1 then 
            point_temp = checkDefensiveNeeds()
            if point_temp then
                if DistanceBetweenLOE(point_temp,1000) then
                    if GetTickCount() > lastTime then
                        lastTime = GetTickCount() + math.random(1200,2000)
                        entropyRun(myHero.x,myHero.z,600)
                        return
                    end
                else
                    CaptureBase(point_temp,"Moving towards defending point") return 
                end
            end
        end
		--A
		if captureCount() == 5 then
			--Defend capture points near the enemy base
			if myHero.z > 5171 and math.sqrt((myHero.x-enemyMidDefendPoint.x)^2+(myHero.z-enemyMidDefendPoint.z)^2)>600 then SmartMove(enemyMidDefendPoint.x, enemyMidDefendPoint.z) return
			elseif myHero.z < 5171 and math.sqrt((myHero.x-enemyBotDefendPoint.x)^2+(myHero.z-enemyBotDefendPoint.z)^2)>600 then SmartMove(enemyBotDefendPoint.x, enemyBotDefendPoint.z) return
			end
		end
		
        
        --ACTUAL CAPTURE LOGIC
		if c_top[1] == 1 and c_mid[1] == 1 and c_bot[1] == 1 and c_tmid[1] ~= 1 and myHero.z >= 7387 then
			if ts.target == nil and math.sqrt((myHero.x-647)^2+(myHero.z-4427)^2)<1000 then CaptureBase(c_tmid[2]) return end
		elseif c_top[1] == 1 and c_mid[1] == 1 and c_bot[1] == 1 and c_tmid[1] == 1 and c_tbot[1] ~= 1 and myHero.z < 7387 then
			if ts.target == nil and math.sqrt((myHero.x-647)^2+(myHero.z-4427)^2)<1000 then CaptureBase(c_tbot[2]) return end
		end
		---capture midLeft if it near and enemy/uncaped
		if (c_mid[1] == 2 or c_mid[1] == 3) and ts.target == nil and c_mid[1] ~= nil then
			if GetInGameTimer() > 80 then
				CaptureBase(c_mid[2]) -- LEFT MID
			end
		return
		end
	
		if c_mid[1] == 1 and (c_top[1] == 3 or c_top[1] == 2) and ts.target == nil then CaptureBase(c_top[2]) return end
		if c_mid[1] == 1 and ts.target == nil and (c_bot[1] == 3 or c_bot[1] == 2) then CaptureBase(c_bot[2]) return end
	
		if ts.target == nil and c_top[1] == 1 and c_mid[1] ~= 1 then CaptureBase(c_mid[2]) return end
		
		--If near bot, and bot is neut/red
		if ts.target == nil and c_top[1] == 1 and c_mid[1] ==1 and c_bot[1] ==1 and c_tmid[1] ~= 1 then CaptureBase(c_tmid[2]) return end
		if ts.target == nil and c_top[1] == 1 and c_mid[1] ==1 and c_bot[1] ==1 and c_tmid[1] == 1 and c_tbot[1] ~= 1 then CaptureBase(c_tbot[2]) return end
	
		
		if captureCount() > 4 then state = "Waiting for next objective" end
		if GetTickCount() > lastTime and captureCount()==5 then
			lastTime = GetTickCount() + math.random(1200,3000)
			--emotional()
			if enemyVisible() then
			entropyRun(myHero.x,myHero.z,600)
			end
			return
		end
	
		end
    end
    --[[ ON TICK ]]--
    function OnTick()
        --if GetTarget() then print(GetTarget().name) end
        if manual or STOP_ACTIVITY then return end
        gameLogic()
        
    end

	if not dom_load then OnLoad() end
	--[[ END OF KEVINBOT DOMINION ]]--
else 	--Validation
	PrintChat("KevinBot - Please do not distribute this script.")
end 	--Validation


