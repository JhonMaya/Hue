<?php exit() ?>--by ferrarino1com 88.8.51.176


--[[honda update function]]

local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/Jusbol/scripts/master/SimpleRivenRelease.lua".."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = SCRIPT_PATH.."SimpleRivenRelease.lua"
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH
 
function _AutoupdaterMsg(msg) print("<font color=\"#6699ff\"><b>Riven, I'm not a Bunny:</b></font> <font color=\"#FFFFFF\">"..msg..".</font>") end
if AUTOUPDATE then
        local ServerData = GetWebResult(UPDATE_HOST, "/Jusbol/scripts/master/VersionFiles/Riven.version")
        if ServerData then
                ServerVersion = type(tonumber(ServerData)) == "number" and tonumber(ServerData) or nil
                if ServerVersion then
                        if tonumber(version) < ServerVersion then
                                _AutoupdaterMsg("New version available"..ServerVersion)
                                _AutoupdaterMsg("Updating, please don't press F9")
                                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () _AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version.") end) end, 3)
                        else
                                _AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
                        end
                end
        else
                _AutoupdaterMsg("Error downloading version info")
        end
end
--[[honda update function]]

-------Variables-------
local myPlayer 									= GetMyHero()
local Passive, temUltimate, AA, UsandoHP		= false, false, false, false
local TickTack, qTick, lTick, rTick, jTick		= 0, 0 ,0, 0, 0
local Target, selectedTarget 					= nil
local lastAttack, lastWindUpTime, lastAttackCD 	= 0, 0, 0
local myTrueRange 								= 0
local IgniteSpell   =   {spellSlot = "SummonerDot", slot = nil, range = 600, ready = false}
local BarreiraSpell =   {spellSlot = "SummonerBarrier", slot = nil, range = 0, ready = false}
-------Variables-------

function OnLoad()
	menu = scriptConfig("Riven by Jus", "JusRivenJusRiven")

	MenuCombo()	--skills and combo key
	MenuSpell()	--multi skill / OnProcessSpell settings
	MenuExtraCombo()	--NoAACombo, items in combo, ignite if killable
	MenuFarm()	--farm sttuff		
	MenuExtra() --potions/barrier
	MenuDraw()	--draw ranges	
	Others()	--others
	MenuPermaShow()
end

function Others()	
	menu:addParam("Version", "Version Info:", SCRIPT_PARAM_INFO, version)
	if myPlayer:GetSpellData(SUMMONER_1).name:find(IgniteSpell.spellSlot) then IgniteSpell.slot = SUMMONER_1
	elseif myPlayer:GetSpellData(SUMMONER_2).name:find(IgniteSpell.spellSlot) then IgniteSpell.slot = SUMMONER_2 end
	if myPlayer:GetSpellData(SUMMONER_1).name:find(BarreiraSpell.spellSlot) then BarreiraSpell.slot = SUMMONER_1
    elseif myPlayer:GetSpellData(SUMMONER_2).name:find(BarreiraSpell.spellSlot) then BarreiraSpell.slot = SUMMONER_2 end
	myTrueRange		= myPlayer.range + GetDistance(myPlayer.minBBox)
	enemyMinions	= minionManager(MINION_ENEMY, 550, myPlayer, MINION_SORT_HEALTH_ASC)
	Jungle 			= minionManager(MINION_JUNGLE, 550, myPlayer, MINION_SORT_MAXHEALTH_DEC)
	menu.combo.key 	= false
	PrintChat("<font color=\"#6699ff\"><b>Riven, I'm not a Bunny by Jus</b></font>")
end

function MenuCombo()
	menu:addSubMenu("[Combo System]", "combo")
	menu.combo:addSubMenu("[Skills Settings]", "skills")
	menu.combo.skills:addParam("q", "Use (Q) in Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo.skills:addParam("w", "Use (W) in Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo.skills:addParam("e", "Use (E) in Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo.skills:addParam("r", "Use (R) in Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo:addParam("key", "Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
end

function MenuSpell()
	menu.combo:addSubMenu("[Multi-Skill System]", "multi")
	menu.combo.multi:addParam("qe", "Try (Q-E) in Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo.multi:addParam("ew", "Try (E-W) in Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo.multi:addParam("qr", "Try (Q-R) in Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo.multi:addParam("er", "Try (E-R) in Combo", SCRIPT_PARAM_ONOFF, true)
end

function MenuExtraCombo()
	menu.combo:addSubMenu("[Extra Combo]", "extracombo")
	menu.combo.extracombo:addParam("smart", "Use Smart Combo", SCRIPT_PARAM_ONOFF, true)
	menu.combo.extracombo:addParam("ignite", "Use Smart Ignite", SCRIPT_PARAM_ONOFF, true)
	menu.combo.extracombo:addParam("items", "Use Items in Combo", SCRIPT_PARAM_ONOFF, true)
end

function MenuFarm()
	menu:addSubMenu("[Farm System]", "farm")
	menu.farm:addParam("lasthit", "Last Hit Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C"))
	menu.farm:addParam("lineclear", "Line Clear Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
	menu.farm:addParam("clearjungle", "Jungle Clear Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
	menu.farm:addSubMenu("[Extra Settings]", "extrafarm")
	menu.farm.extrafarm:addParam("delay", "Extra Delay to Hit Minions", SCRIPT_PARAM_SLICE, 0, -3, 3, 0)
	menu.farm.extrafarm:addParam("jungles", "[Jungle Skills]", SCRIPT_PARAM_INFO, "SET")
	menu.farm.extrafarm:addParam("q", "Use (Q) in Jungle", SCRIPT_PARAM_ONOFF, true)
	menu.farm.extrafarm:addParam("w", "Use (w) in Jungle", SCRIPT_PARAM_ONOFF, true)
	menu.farm.extrafarm:addParam("e", "Use (E) in Jungle", SCRIPT_PARAM_ONOFF, true)
end

function MenuExtra()
	menu:addSubMenu("[Others System]", "extra")
	menu.extra:addParam("systemextra", "Use Extra System", SCRIPT_PARAM_ONOFF, true)
	menu.extra:addParam("hp", "Auto HP Potion if Health < %", SCRIPT_PARAM_SLICE, 70, 0, 90, 0)
	menu.extra:addParam("barrier", "Auto Barrier if Health < %", SCRIPT_PARAM_SLICE, 40, 0, 90, 0)
end

function MenuDraw()
	menu:addSubMenu("[Draw System]", "draw")        
    menu.draw:addParam("Q", "Draw (Q) range", SCRIPT_PARAM_ONOFF, false)
    menu.draw:addParam("W", "Draw (W) range", SCRIPT_PARAM_ONOFF, true)
    menu.draw:addParam("E", "Draw (E) range", SCRIPT_PARAM_ONOFF, true)
    menu.draw:addParam("R", "Draw (R) range", SCRIPT_PARAM_ONOFF, false)
    menu.draw:addParam("", "", SCRIPT_PARAM_INFO, "")
    menu.draw:addParam("target", "Draw Target", SCRIPT_PARAM_ONOFF, true)
end


-- function MenuSystem()
-- 	menu:addSubMenu("[General System]", "system")
-- 	menu.system:addParam("packet", "Use Packet to help Passive", SCRIPT_PARAM_ONOFF, true)
-- 	--menu.system:addParam("")
-- end

function MenuPermaShow()
	menu:permaShow("Version")
	menu.combo:permaShow("key")
	menu.farm:permaShow("lasthit")
	menu.farm:permaShow("lineclear")
	menu.farm:permaShow("clearjungle")
end


function OnSendPacket(p)
	-- local packetQ	=	menu.system.packet
	local myPacket 	= 	Packet(p)
	if myPacket:get('name') == 'S_CAST' and myPacket:get('spellId') == _Q then
		if ValidTarget(Target) then
			if Passive and GetDistance(Target) <= myTrueRange then			
				myPacket:block()
			elseif AA and GetDistance(Target) <= myTrueRange then
				myPacket:block()			
			end
		end
		-- p = CLoLPacket(71)
		-- p:EncodeF(myPlayer.networkID)
		-- p:Encode1(0)
		-- p.dwArg1 = 0
		-- p.dwArg2 = 0
		-- SendPacket(p)
		p.pos = 1
		p = CLoLPacket(65)
        p:EncodeF(myPlayer.networkID)
        p:Encode1(0)
        p.dwArg1 = 0
        p.dwArg2 = 0
        SendPacket(p)	
		
	end
end


-- function OnRecvPacket(p)
-- 	--local myPacket = Packet(p)
-- 	p.pos = 1
-- 	p = CLoLPacket(0x65)
-- 	p:EncodeF(myPlayer.networkID)
-- 	p:Encode1(0)
-- 	p.dwArg1 = 0
-- 	p.dwArg2 = 0
-- 	SendPacket(p)
-- end


function OnGainBuff(unit, buff)
	if unit.isMe then
		if buff.name:lower():find("rivenpassiveaaboost") then Passive 		= true end
		if buff.name:lower():find("rivenwindslashready") then temUltimate 	= true myTrueRange = myTrueRange + 75 end
		if buff.name:lower():find("regenerationpotion")	 then UsandoHP		= true end
	end
end

function OnLoseBuff(unit, buff)
	if unit.isMe then
		if buff.name:lower():find("rivenpassiveaaboost") then Passive 		= false end
		if buff.name:lower():find("rivenwindslashready") then temUltimate 	= false myTrueRange = myTrueRange -75 end
		if buff.name:lower():find("regenerationpotion")	 then UsandoHP		= false end
	end
end

function FarmChicken()
	local delay_	=	menu.farm.extrafarm.delay
	enemyMinions:update()
	if os.clock() + (GetLatency() * 1000) / 2 > TickTack then
		myPlayer:MoveTo(mousePos.x, mousePos.z)
	end
	for index, minion in pairs(enemyMinions.objects) do
        local aDmg = getDmg("AD", minion, myPlayer)
        if ValidTarget(minion) and minion.health <= aDmg  and GetDistance(minion) <= myTrueRange and os.clock() + ((GetLatency() * 1000) / 2) > TickTack then
            myPlayer:Attack(minion)
            TickTack = os.clock() + ((GetLatency() * 1000) / 2)  + 0.625 + lastWindUpTime + delay_
        end
     end
end
-- items
local Items = {
["Brtk"]        =       {ready = false, range = 450, SlotId = 3153, slot = nil},
["Bc"]          =       {ready = false, range = 450, SlotId = 3144, slot = nil},
["Rh"]          =       {ready = false, range = 400, SlotId = 3074, slot = nil},
["Tiamat"]      =       {ready = false, range = 400, SlotId = 3077, slot = nil},
["Hg"]          =       {ready = false, range = 700, SlotId = 3146, slot = nil},
["Yg"]          =       {ready = false, range = myTrueRange, SlotId = 3142, slot = nil},
["RO"]          =       {ready = false, range = 500, SlotId = 3143, slot = nil},
["SD"]          =       {ready = false, range = myTrueRange, SlotId = 3131, slot = nil},
["MU"]          =       {ready = false, range = myTrueRange, SlotId = 3042, slot = nil} }
local HP_MANA     = { ["Hppotion"] = {SlotId = 2003, ready = false, slot = nil} }
local FoundItems  = {}

-- cast items
function CheckItems(tabela)
    for ItemIndex, Value in pairs(tabela) do
        Value.slot = GetInventorySlotItem(Value.SlotId)
            if Value.slot ~= nil and (myPlayer:CanUseSpell(Value.slot) == READY) then
            FoundItems[#FoundItems+1] = ItemIndex
        end
    end
end

function CastCommonItem()
	local items_suv =	menu.extra.systemextra
	if not items_suv then return end
    CheckItems(Items)
    if #FoundItems ~= 0 then
        for i, Items_ in pairs(FoundItems) do
            if ValidTarget(Target) then                            
                if GetDistance(Target) <= Items[Items_].range then
                    if Items_ == "Brtk" or Items_ == "Bc" then
                        CastSpell(Items[Items_].slot, Target)
                    else
                        CastSpell(Items[Items_].slot)
                    end
            	end
            end
            FoundItems[i] = nil
        end    
    end
end
 
function CastSurviveItem()
	local items_suv	=	menu.extra.systemextra
	if not items_suv then return end
    CheckItems(HP_MANA)    
    local hp_                		= menu.extra.hp   
    local barrier_                  = menu.extra.barrier
    local myPlayerhp_               = (myPlayer.health / myPlayer.maxHealth *100)
    if #FoundItems ~= 0 then        
        for i, HP_MANA_ in pairs(FoundItems) do
            if HP_MANA_ == "Hppotion" and myPlayerhp_ <= hp_ and not InFountain() and not UsandoHP then
               CastSpell(HP_MANA[HP_MANA_].slot)
            end
        	FoundItems[i] = nil
        end
        if BarreiraSpell.slot ~= nil and myPlayerhp_ <= barrier_ and not InFountain() then
            CastSpell(BarreiraSpell.slot)
        end
    end
end

function LineFarm()      
    local qReady    =       myPlayer:CanUseSpell(_Q) == READY
    local eReady    =       myPlayer:CanUseSpell(_E) == READY
    local wReady    =       myPlayer:CanUseSpell(_W) == READY
    local delay_	=		menu.farm.extrafarm.delay
    --[[items]]
   -- local items_0   =       menu.combo.extra.items  
    enemyMinions:update()   
    if os.clock() + ((GetLatency() * 1000) / 2) > lTick then
		myPlayer:MoveTo(mousePos.x, mousePos.z)
	end
    for i, Minion in pairs(enemyMinions.objects) do
        if Minion ~= nil and not Minion.dead then
        	local aDmg = getDmg("AD", Minion, myPlayer)
           	if Passive and Minion.health <= aDmg and GetDistance(Minion) <= myTrueRange and os.clock() + ((GetLatency() * 1000) / 2) > lTick then
            	myPlayer:Attack(Minion)
             	lTick =  os.clock() + ((GetLatency() * 1000) / 2)  + 0.625 + 0.2 + delay_
           
	        elseif qReady then
	           CastQ(Minion)

            elseif eReady then
                CastE(Minion)

            elseif wReady then
                CastW(Minion) 

            end
        end
    end
end

function CastQ(myTarget)
	if not myPlayer.canAttack then return end
	local useq		=	menu.combo.skills.q
	local qReady	=	myPlayer:CanUseSpell(_Q) == READY
	if useq and qReady and ValidTarget(myTarget) and GetDistance(myTarget) <= 550 then
		if not Passive then
			CastSpell(_Q, myTarget.x, myTarget.z)
		elseif Passive and GetDistance(myTarget) > myTrueRange then
			CastSpell(_Q, myTarget.x, myTarget.z)
		elseif Passive and os.clock() + (GetLatency() * 1000) / 2 > qTick + 4.5 then
			CastSpell(_Q, myTarget.x, myTarget.z)
		end
	end
end

function CastW(myTarget)
	--if not myPlayer.canAttack then return end
	local usew		=	menu.combo.skills.w
	local wReady 	= 	myPlayer:CanUseSpell(_W) == READY
	local wRange 	= 	285
	local useItems_	=	menu.combo.extracombo.items
	if temUltimate then wRange = wRange + 10 else wRange = 285 end
	if usew and wReady and ValidTarget(myTarget) then
		if GetDistance(myTarget) <= wRange then
			CastSpell(_W)
		end
	end
	if useItems_ then CastCommonItem() end
end

function CastE(myTarget)
	--if not myPlayer.canAttack then return end
	local usee		=	menu.combo.skills.e
	local eReady	=	myPlayer:CanUseSpell(_E) == READY
	if usee and eReady and ValidTarget(myTarget) then
		if not Passive and GetDistance(myTarget) <= 385 then
			CastSpell(_E, myTarget.x, myTarget.z)
		elseif Passive and GetDistance(myTarget) >= myTrueRange and GetDistance(myTarget) <= 385 then
			CastSpell(_E, myTarget.x, myTarget.z)
		end
	end
end

function CastR(myTarget)
	--if not myPlayer.canAttack then return end
	local user 		=	menu.combo.skills.r
	local rReady 	=	myPlayer:CanUseSpell(_R) == READY
	local eReady	=	myPlayer:CanUseSpell(_E) == READY
	if user and rReady and ValidTarget(myTarget) then
		--rTick = os.clock() + GetLatency() / 2 * 1000
		local rlvl 		=	myPlayer:GetSpellData(_R).level
		local DamageR	=	getDmg("R", myTarget, myPlayer, rlvl)
		if rReady and not temUltimate and not eReady then
			CastSpell(_R)			
		elseif temUltimate and myTarget.health <= DamageR then
			CastSpell(_R, myTarget.x, myTarget.z)
		elseif temUltimate and os.clock() + (GetLatency() * 1000) / 2 > rTick + 13 then
			CastSpell(_R, myTarget.x, myTarget.z)
		end
	end
end

function OnProcessSpell(unit, spell)	
	if unit.isMe then
		-------ready--------
		local qReady 		= 	myPlayer:CanUseSpell(_Q) == READY
		local wReady 		= 	myPlayer:CanUseSpell(_W) == READY
		local eReady 		= 	myPlayer:CanUseSpell(_E) == READY
		local rReady 		= 	myPlayer:CanUseSpell(_R) == READY
		--------use---------
		local useq			=	menu.combo.skills.q
		local usew			=	menu.combo.skills.w
		local usee 			=	menu.combo.skills.e
		local user 			=	menu.combo.skills.r
		-------multi skill-------
		local qe_	 		=	menu.combo.multi.qe
		local ew_	 		=	menu.combo.multi.ew
		local qr_			=	menu.combo.multi.qr
		local er_			=	menu.combo.multi.er
		local useItems_		=	menu.combo.extracombo.items
		local ComboON 		= 	menu.combo.key

		if spell.name:lower():find("attack") then
			--[[AA-Skill]]
			if ComboON then
				if useq and qReady and ValidTarget(Target) then
			 		CastQ(Target)
				
				-- elseif usee and eReady then
				-- 	CastE(Target)
				-- elseif usew and wReady then
				-- 	CastW(Target)
				end
			end
			
			--[[orbwalk]]
			lastAttack 		= os.clock() - (GetLatency() * 1000) / 2
			lastWindUpTime 	= spell.windUpTime 
			lastAttackCD	= spell.animationTime
		end

		if spell.name:lower():find("riventricleaver") then
			Packet('S_MOVE', {x = Target.x + 50, y = Target.z}):send()
			Packet('S_MOVE', {x = Target.x - 50, y = Target.z}):send()

			qTick = os.clock() + (GetLatency() * 1000) / 2
			
			local rlvl 		=	myPlayer:GetSpellData(_R).level
			if ValidTarget(Target) and qr_ and user then
				local DamageR	=	getDmg("R", Target, myPlayer, rlvl)
				if temUltimate and Target.health <= DamageR then
					CastSpell(_R, Target.x, Target.z)
				elseif temUltimate and os.clock() + (GetLatency() * 1000) / 2 > rTick + 13 then
					CastSpell(_R, Target.x, Target.z)
				end
			end

		end		

		if spell.name:lower():find("rivenfeint") then
			--qTick = os.clock() + GetLatency() / 2 * 1000
			local rlvl 		=	myPlayer:GetSpellData(_R).level			
			if ValidTarget(Target) then
				local DamageR	=	getDmg("R", Target, myPlayer, rlvl)
				if er_ and user and temUltimate and Target.health <= DamageR then
					CastSpell(_R, Target.x, Target.z)
				elseif user and temUltimate and qr_ and os.clock() + (GetLatency() * 1000) / 2 > rTick + 13 then
					CastSpell(_R, Target.x, Target.z)			
				elseif usew and wReady and ew_ then
					CastW(Target)
					if useItems_ then CastCommonItem() end
				end
			end
		end

		if spell.name:lower():find("rivenfengshuiengine") then
			rTick = os.clock() + (GetLatency() * 1000) / 2
		end

	end
end

function NormalCombo(myTarget)
-- print("-------NORMAL****-----------")	
	CastQ(myTarget)
	CastE(myTarget)
	CastR(myTarget)
	CastW(myTarget)	
end

function NoAACombo(myTarget)
	-- print("------- NO AA-----------")
	-------ready--------
	local qReady = myPlayer:CanUseSpell(_Q) == READY
	local wReady = myPlayer:CanUseSpell(_W) == READY
	local eReady = myPlayer:CanUseSpell(_E) == READY
	local rReady = myPlayer:CanUseSpell(_R) == READY
	--------use---------
	local useq	=	menu.combo.skills.q
	local usew	=	menu.combo.skills.w
	local usee 	=	menu.combo.skills.e
	local user 	=	menu.combo.skills.r
	-------items--------
	local useItems_		=	menu.combo.extracombo.items
	if ValidTarget(myTarget) then
		if useq and qReady then
			if GetDistance(myTarget) <= 550 then
				CastSpell(_Q, myTarget.x, myTarget.z)
			elseif os.clock() + (GetLatency() * 1000) / 2 > qTick + 4.5 and GetDistance(Target) <= 550 then
				CastSpell(_Q, myTarget.x, myTarget.z)
			end
		elseif usee and eReady and GetDistance(myTarget) <= 385 then
			CastSpell(_E, myTarget.x, myTarget.z)
		elseif usew and wReady then
			local wRange 	= 	285
			if temUltimate then wRange = wRange + 10 else wRange = 285 end
				if	GetDistance(myTarget) <= wRange then
					CastSpell(_W)
				end
				if useItems_ then CastCommonItem() end
		elseif user and rReady then
			if GetDistance(myTarget) <= 385 and not temUltimate then
				CastSpell(_R)				
			elseif temUltimate then
				local rlvl 		=	myPlayer:GetSpellData(_R).level
				local DamageR	=	getDmg("R", myTarget, myPlayer, 2, rlvl) 
				if myTarget.health <= DamageR then	
					CastSpell(_R, myTarget.x, myTarget.z)
				elseif os.clock() + (GetLatency() * 1000) / 2 > rTick + 13 then
					CastSpell(_R, myTarget.x, myTarget.z)
				end
			end
		else
			BurnYouShit(myTarget)
		end
	end
end

function KillNoAA(myTarget)
	local dmgTable = {"Q", "W", "R", "IGNITE"}
	local skiTable = {_Q, _W, _R, IgniteSpell.slot}
	local totalDmg = 0
	if ValidTarget(myTarget) then
		for i=1, #dmgTable do
			for a=1, #skiTable do			
				local spellReady 	=	myPlayer:CanUseSpell(skiTable[a]) == READY
				local spelllvl		=	myPlayer:GetSpellData(skiTable[a]).level
				if spellReady and spelllvl > 0 and ValidTarget(myTarget) then
					if dmgTable[i] == "Q" then
						totalDmg = totalDmg + getDmg(dmgTable[i], myTarget, myPlayer, 2, spelllvl)
					elseif dmgTable[i] == "R" then
						totalDmg = totalDmg + getDmg(dmgTable[i], myTarget, myPlayer, 2, spelllvl)
					elseif dmgTable[i] == "W" then
						totalDmg = totalDmg + getDmg(dmgTable[i], myTarget, myPlayer, 1, spelllvl)
					--elseif dmgTable[i] == "IGNITE" and spellReady then
					--	totalDmg = totalDmg + getDmg(dmgTable[i], myTarget, myPlayer, myPlayer.level)
					end
				end
			end
		end
		if totalDmg ~= 0 and myTarget.health < totalDmg then
			AA = false
			return true
		else
			AA = true
			return false
		end
	end
end

function BurnYouShit(myTarget)
	local useI 	=	menu.combo.extracombo.ignite
	local slot_	=	IgniteSpell.slot
	if useI and slot_ ~= nil and myPlayer:CanUseSpell(slot_) == READY and ValidTarget(myTarget) and GetDistance(myTarget) <= IgniteSpell.range and not TargetHaveBuff(IgniteSpell.spellSlot, myTarget) then
		local iDmg	=	getDmg("IGNITE", myTarget, myPlayer, myPlayer.level)
		if myTarget.health <= iDmg then
			CastSpell(slot_, myTarget)
		end
	end
end

function JungleBitch()
	local useq			=	menu.farm.extrafarm.q
	local usew			=	menu.farm.extrafarm.w
	local usee 			=	menu.farm.extrafarm.e
	local delay_		=	menu.farm.extrafarm.delay
	local KillMobs		=	menu.farm.clearjungle
	if not KillMobs then return end
	Jungle:update()
	if os.clock() + (GetLatency() * 1000) / 2 > jTick then
		myPlayer:MoveTo(mousePos.x, mousePos.z)
	end
	local Minion = Jungle.objects[1]
	if Minion ~= nil then
		if useq then
			CastSpell(_Q, Minion.x, Minion.z)
		elseif usew then
			CastSpell(_W)
		elseif usee then
			CastSpell(_E, Minion.x, Minion.z)
		end
	end
	if ValidTarget(Minion) and os.clock() + ((GetLatency() * 1000) / 2) > jTick then
        myPlayer:Attack(Minion)
        jTick = os.clock() + ((GetLatency() * 1000) / 2)  + 0.625 + lastWindUpTime + delay_
   	end
end

function F5Target()	
	selectedTarget = GetTarget()
    if selectedTarget ~= nil and selectedTarget.team ~= myPlayer.team and selectedTarget.type == myPlayer.type and selectedTarget.visible then
    	return selectedTarget
    end           
end
	
function OnTick()
	if myPlayer.dead then return end
	local ComboON 		= 	menu.combo.key
	local FarmChicken_	=	menu.farm.lasthit
	local LineFarm_		=	menu.farm.lineclear
	local BeSmart		=	menu.combo.extracombo.smart
	
	Target = F5Target()
	if ComboON then
		if BeSmart then	
			if KillNoAA(Target) then
				NoAACombo(Target)
			else
				NormalCombo(Target)
			end
		else
			NormalCombo(Target)
		end
		OrbWalk(Target)		
	end
	if FarmChicken_ then FarmChicken() end
	if LineFarm_ then LineFarm() end
	JungleBitch()
	--CastCommonItem()
	BurnYouShit(Target)
	CastSurviveItem()
end

function OrbWalk(myTarget)
	if ValidTarget(myTarget, myTrueRange) then
		if timeToShoot() then
			myHero:Attack(myTarget)
		elseif heroCanMove() then
			moveToCursor()
		end
	else
		moveToCursor()
	end
end

function heroCanMove()
	return ( os.clock() + (GetLatency() * 1000) / 2 > lastAttack + lastWindUpTime )
end 
 
function timeToShoot()
	return ( os.clock() + (GetLatency() * 1000) / 2 > lastAttack + lastAttackCD + 0.2 ) and myPlayer.canAttack
end 
 
function moveToCursor()
	if GetDistance(mousePos) > myTrueRange then
		local moveToPos = myPlayer + (Vector(mousePos) - myPlayer):normalized()* 180
		myHero:MoveTo(moveToPos.x, moveToPos.z)
	end 
end

function OnWndMsg(Msg, Key)
	if myPlayer.dead then return end
	if Msg == WM_LBUTTONDOWN then
		if Target ~= nil then
			print("<font color=\"#6699ff\"><b>Riven - Target selected: </b></font><font color=\"#6600FF\">"..Target.charName.."</font>")
		end
	end
end

--[[Credits to barasia, vadash and viseversa for anti-lag circles]]--
function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
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
    if myPlayer.dead then return end
    local qRange    		=       275
    local wRange    		=       260
    local eRange    		=       385
    local rRange    		=       900
    --[[menu draw]]
	local qDraw				=       menu.draw.Q
	local wDraw				=       menu.draw.W
	local eDraw				=       menu.draw.E
	local rDraw				=       menu.draw.R
    local targetDraw        =       menu.draw.target
    if qDraw then
        DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, qRange, ARGB(255, 255, 000, 000))
    end
    if wDraw then
        DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, wRange, ARGB(255, 000, 255, 000))
    end
    if eDraw then
        DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, eRange, ARGB(255, 251, 255, 000))
    end
    if rDraw then
       	DrawCircle2(myPlayer.x, myPlayer.y, myPlayer.z, rRange, ARGB(255, 255, 255, 000))
    end   
    if targetDraw and Target ~= nil then
        for i=0, 3, 1 do
            DrawCircle2(Target.x, Target.y, Target.z, 80 + i , ARGB(255, 255, 000, 255))    
        end
    end
end