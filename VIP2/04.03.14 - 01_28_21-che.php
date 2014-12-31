<?php exit() ?>--by che 91.8.149.117
if myHero.charName ~= "Ryze" or not VIP_USER then return end
--Spells
local Q = {
	Range = 625,
	Damage = 25 * GetSpellData(_Q).level + 35 + .4 * myHero.ap + .065 * myHero.maxMana,
	BonusCR = 2 * GetSpellData(_Q).level, --2 4 6 8 10
	CastTime = 265,
	Cooldown = 3500 * (1 + myHero.cdr),
	Level = 0
}
local W = {
	Range = 600,
	Damage = 35 * GetSpellData(_W).level + 25 + .6 * myHero.ap + .045 * myHero.maxMana,
	RootDuration = .5 + .25 * GetSpellData(_W).level, -- 0,75 1 1,25 1,5 1,75 
	CastTime = 250,
	Cooldown = 14000 * (1 + myHero.cdr),
	Level = 0
	
}
local E = {
	Range = 600,
	Damage = 20 * GetSpellData(_E).level + 30 + .35 * myHero.ap + .01 * myHero.maxMana,
	Bounces = 5,
	MResDebuff = 9 + 3 * GetSpellData(_E).level, --12 15 18 21 24 
	CastTime = 280,
	Cooldown = 14000 * (1 + myHero.cdr),
	Level = 0
}
local R = {
	Duration = 4 + GetSpellData(_R).level,
	spellVamp = .10 + GetSpellData(_R).level * .05,
	MovementSpeed = 80,
	Splash = .5,
	SplashRange = 200,
	Level = 0
}

local Ignite = {
	Damage = 50 + 20 * myHero.level,
	Slot = nil
}

local enemies = {}

local activeItems = {
	DFG = 3128,
	HXG = 3146,
	BWC = 3144,
	SHEEN = 3057,
	TRINITY = 3078,
	ICEBORN = 3025,
	LICHBANE = 3100,
	MURAMANA = 3042,
}

local Object

local ZyraP = false

function OnLoad()
	PrintChat("<font color='#00FFEE'>>> Legendary Ryze loaded</font>")
	
	--Config
	Config = scriptConfig("Legendary Ryze", "ryze.cfg") 
	Config:addSubMenu("Basic Settings", "Basic")
	Config:addSubMenu("Move to Mouse Settings", "MTM")
	Config:addSubMenu("Item Settings", "Items")
	Config:addSubMenu("Farming Settings", "Farming")
	Config:addSubMenu("Exploit Settings", "Exploits")
	Config:addSubMenu("Drawing Settings", "Drawing")
	
	Config.Basic:addParam("Harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, 84) 
	Config.Basic:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	Config.Basic:addParam("SafeDistance", "Distance to feel safe", SCRIPT_PARAM_SLICE, 400, 0, Q.Range, 0)
	Config.Basic:addParam("LongCDR", "CDR to trigger long combo", SCRIPT_PARAM_SLICE, 35, 0, 40, 0)
	Config.Basic:addParam("Ignite", "Automatically use ignite", SCRIPT_PARAM_ONOFF, false)
	Config.Basic:addParam("Ult", "Use ult in combo", SCRIPT_PARAM_ONOFF, false)
	Config.Basic:addParam("SingleUlt", "Use ult against a single target", SCRIPT_PARAM_ONOFF, false)
	Config.Basic:addParam("stutter", "Stutterwalk", SCRIPT_PARAM_ONOFF, false)
	
	Config.Basic:permaShow("Ignite") 
	Config.Basic:permaShow("Harass") 
	Config.Basic:permaShow("Combo")
	
	Config.MTM:addParam("MTMHarassT", "MTM while harassing (if we got a target)", SCRIPT_PARAM_ONOFF, false)
	Config.MTM:addParam("MTMComboT", "MTM while using Combo (if we got a target)", SCRIPT_PARAM_ONOFF, false)
	Config.MTM:addParam("MTMHarass", "MTM while harassing", SCRIPT_PARAM_ONOFF, false)
	Config.MTM:addParam("MTMCombo", "MTM while using Combo", SCRIPT_PARAM_ONOFF, false)
	
	Config.Items:addParam("lb", "Lich Bane/Trinity Force/Iceborn Gauntlet", SCRIPT_PARAM_ONOFF, true)
	Config.Items:addParam("dfg", "Deathfire Grasp", SCRIPT_PARAM_ONOFF, false)
	Config.Items:addParam("hxg", "Hextech Gunblade/Bilgewater Cutlass", SCRIPT_PARAM_ONOFF, false)
	Config.Items:addParam("muramana", "Muramana", SCRIPT_PARAM_ONOFF, false)
	Config.Items:addParam("minmanamura", "Min Mana Muramana (Percent)", SCRIPT_PARAM_SLICE, 25, 0, 100, 2)
	
	Config.Farming:addParam("comboFarm", "Farm while using Combo", SCRIPT_PARAM_ONOFF, false)
	Config.Farming:addParam("harassFarm", "Farm while harassing", SCRIPT_PARAM_ONOFF, false)
	Config.Farming:addParam("Qfarm", "Stack Tear/QFarm", SCRIPT_PARAM_ONOFF, false)
	--Config.Farming:addParam("AAfarm", "AA Farm", SCRIPT_PARAM_ONOFF, false)
	Config.Farming:addParam("QWEfarm", "QWE Farm", SCRIPT_PARAM_ONOFF, false)
	Config.Farming:addParam("Farm", "Farm toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, 73)
	Config.Farming:addParam("Farm", "Farm", SCRIPT_PARAM_ONKEYDOWN, false, 65)
	
	Config.Farming:permaShow("Farm")
	
	
	Config.Exploits:addSubMenu("No Face-Direction", "NFD")
	Config.Exploits.NFD:addParam("info", "Ignore where your champ is looking at while casting spells", SCRIPT_PARAM_INFO, "")
	Config.Exploits.NFD:addParam("NFDq", "Cast Q without Face-Direction", SCRIPT_PARAM_ONOFF, true)
	Config.Exploits.NFD:addParam("NFDw", "Cast W without Face-Direction", SCRIPT_PARAM_ONOFF, true)
	Config.Exploits.NFD:addParam("NFDe", "Cast E without Face-Direction", SCRIPT_PARAM_ONOFF, true)
	Config.Exploits.NFD:addParam("manNFD0", "Manually cast Q without Face-Direction", SCRIPT_PARAM_ONOFF, false)
	Config.Exploits.NFD:addParam("manNFD1", "Manually cast W without Face-Direction", SCRIPT_PARAM_ONOFF, false)
	Config.Exploits.NFD:addParam("manNFD2", "Manually cast E without Face-Direction", SCRIPT_PARAM_ONOFF, false)
	
	Config.Exploits:addSubMenu("Passive Proc Exploit", "PassiveExploit")
	Config.Exploits.PassiveExploit:addParam("info", "This exploit procs your passive after doing lasthits", SCRIPT_PARAM_INFO, "")
	Config.Exploits.PassiveExploit:addParam("PE", "Use passive exploit", SCRIPT_PARAM_ONOFF, true)
	Config.Exploits.PassiveExploit:addParam("NoW", "Never ever try to use W", SCRIPT_PARAM_ONOFF, true)
	Config.Exploits.PassiveExploit:addParam("AARange", "Range for proccing after AA lasthit", SCRIPT_PARAM_SLICE, 350, 0, 550, 0)
	Config.Exploits.PassiveExploit:addParam("QRange", "Range for proccing after Q lasthit", SCRIPT_PARAM_SLICE, 200, 0, 550, 0)
	
	Config.Drawing:addParam("DrawAARange", "Draw AA Range", SCRIPT_PARAM_ONOFF, true)
	Config.Drawing:addParam("DrawSkillRange", "Draw Skill Range", SCRIPT_PARAM_ONOFF, true)
	Config.Drawing:addParam("DrawSplashRange", "Draw R Splash Range", SCRIPT_PARAM_ONOFF, true)
	Config.Drawing:addParam("DrawProjectiles", "Draw flying Projectiles (AAs, Spells)", SCRIPT_PARAM_ONOFF, true)
	Config.Drawing:addParam("DrawAATarget", "Draw Autoattack Target", SCRIPT_PARAM_ONOFF, true)
	Config.Drawing:addParam("DrawTarget", "Draw Target", SCRIPT_PARAM_ONOFF, true)
	Config.Drawing:addParam("DrawText", "Draw Text", SCRIPT_PARAM_ONOFF, true)
	
	--Target Selector
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, Q.Range, DAMAGE_MAGIC, false)
	ts.name = "Ryze"
	Config:addTS(ts)
	
	--get enemies
	for i = 1, heroManager.iCount do
		local hero = heroManager:getHero(i)
	--	if hero.team ~= myHero.team then
			enemies[i] = {champ = hero.charName, timetokill = 0, count = i}
		--end
	end
	
	--get ignite
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
		Ignite.Slot = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
		Ignite.Slot = SUMMONER_2
	else
		Ignite.Slot = nil
	end	
end

function OnUnload()
	PrintChat("<font color='#FFAA00'>>> Legendary Ryze unloaded</font>")
end

function OnCreateObj(obj)
	if obj.name == "VengefulPlant" then
		ZyraP = true
	end
end

function OnDeleteObj(obj)
	if obj.name == "VengefulPlant" then
		ZyraP = false
	end
end

function validTarget(target)
	if target then
		if target ~= nil then
			if target.health then
				if target.visible then
					if ValidTarget(target, Q.Range) then
						if target.charName ~= "Zyra" then
							return true
						elseif ZyraP == true or objManager:GetObjectByNetworkId(target.networkID).name ~= "VengefulPlant" then --screw you zyra
							return true
						end
					end
				end
			end
		end
	end
	return false
end

_CastSpell = CastSpell
function CastSpell(spell)
	if myHero:CanUseSpell(spell) then
		if spell ~= _R then
			if validTarget(ts.target) then
				CastSpell(spell, ts.target)
			end
		else
			_CastSpell(_R)
		end
	end
end

function CastSpell(spell, target)
	if myHero:CanUseSpell(spell) and validTarget(target) and target.networkID ~= nil then
		if (spell == _Q and Config.Exploits.NFD.NFDq) or (spell == _W and Config.Exploits.NFD.NFDw) or (spell == _E and Config.Exploits.NFD.NFDe) then
			CastSpellPacket(spell, target.networkID)
		else
			_CastSpell(spell, target)
		end
	end
	if Config.Basic.stutter and validTarget(target) then
		Packet('S_MOVE', {x = myHero.x, y = myHero.z+5}):send()
	end
end

function CastSpellPacket(spell, targetNWID)
	if targetNWID ~= nil then
		packet = CLoLPacket(0x99)
			packet:EncodeF(myHero.networkID)
			packet:Encode1(spell)
			packet:EncodeF(myHero.x)
			packet:EncodeF(myHero.z)
			packet:EncodeF(0)
			packet:EncodeF(0)
			packet:EncodeF(targetNWID)
			packet.dwArg1 = 1
			packet.dwArg2 = 0
		SendPacket(packet)
	end
end

function OnSendPacket(p)
	if p.header == 0x99 then
		p.pos = 5
		local spell = p:Decode1()
		if Config.Exploits["manNFD0"..spell] then
			p.pos = 22
			local SpellNWID = p:DecodeF()
			if SpellNWID ~= nil and SpellNWID ~= 0 then
				p:Block()
				CastSpellPacket(spell, SpellNWID)
			end
		end
	end
end

function OnProcessSpell(Object, Spell)
	if Object.isMe then
		attackTarget = Spell.target
		if (Spell.name:find("Attack") or Spell.name == GetSpellData(_Q).name) and attackTarget and attackTarget.valid then
			NWID = Spell.projectileID
		end
	end
end

function UpdateSpells()
	Q.Level = GetSpellData(_Q).level
	Q.Damage = 25 * Q.Level + 35 + .4 * myHero.ap + .065 * myHero.maxMana
	Q.BonusCR = 2 * Q.Level
	Q.Cooldown = 3500 * (1 + myHero.cdr)
	
	W.Level = GetSpellData(_W).level
	W.Damage = 35 * GetSpellData(_W).level + 25 + .6 * myHero.ap + .045 * myHero.maxMana
	W.RootDuration = .5 + .25 * GetSpellData(_W).level
	W.Cooldown = 14000 * (1 + myHero.cdr)
	
	E.Level = GetSpellData(_E).level
	E.Damage = 20 * E.Level + 30 + .35 * myHero.ap + .01 * myHero.maxMana
	E.MResDebuff = 9 + 3 * E.Level
	E.Cooldown = 14000 * (1 + myHero.cdr)
	
	R.Level = GetSpellData(_R).level
	R.Duration = 4 + R.Level
	R.spellVamp = .10 + R.Level * .05
	
	Ignite.Damage = 50 + 20 * myHero.level
end

function underTurret()
	for _, turret in pairs(GetTurrets()) do
		if turret.team == player.team then
			if validTarget(ts.target) and GetDistance(ts.target, turret) < 950 then
				return true
			end
		end
	end
	return false
end

--dot qwqeq 20%+cr
function DoT()
	if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
	if myHero:CanUseSpell(_W) == READY then CastSpell(_W, ts.target) end
	if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
	if myHero:CanUseSpell(_E) == READY then CastSpell(_E, ts.target) end
	if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
end

--burst qweq
function Burst()
	if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
	if myHero:CanUseSpell(_W) == READY then CastSpell(_W, ts.target) end
	if myHero:CanUseSpell(_E) == READY then CastSpell(_E, ts.target) end
	if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
end
function BurstDamage()
	QDmg = getDmg("Q", ts.target, myHero)
	WDmg = getDmg("W", ts.target, myHero)
	EDmg = getDmg("E", ts.target, myHero)
	return (QDmg + WDmg + EDmg + QDmg)
end

--fleeing wqeq
function FleeingCombo()
	if myHero:CanUseSpell(_W) == READY then CastSpell(_W, ts.target) end
	if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
	if myHero:CanUseSpell(_E) == READY then CastSpell(_E, ts.target) end
	if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
end

function CountEnemys(range)
	local enemyInRange = 0
	for i = 1, heroManager.iCount, 1 do
		local hero = heroManager:getHero(i)
		if  GetDistance(ts.target, hero) <= range then
			enemyInRange = enemyInRange + 1
		end
	end
    return enemyInRange
end

function PassiveExploit()
if NWID then
	Object = objManager:GetObjectByNetworkId(NWID)
	if Object and Object.valid then
		if myHero:CanUseSpell(_Q) == READY then
			if GetDistance(attackTarget, Object) < Config.Exploits.PassiveExploit.AARange and attackTarget.health < myHero.totalDamage then
				CastSpell(_Q, attackTarget)
				CastSpell(_E, attackTarget)
				if not Config.Exploits.PassiveExploit then CastSpell(_W, attackTarget) end
				
				attackTarget = nil
				NWID = nil
			end
		elseif GetDistance(attackTarget, Object) < Config.Exploits.PassiveExploit.QRange and attackTarget.health < Q.Damage then
			CastSpell(_E, attackTarget)
			if not Config.Exploits.PassiveExploit then CastSpell(_W, attackTarget) end
			
			attackTarget = nil
			NWID = nil
		end
		end
	end
end

function UpdateTimers()
	if enemies ~= nil then
		for _, enemy in ipairs(enemies) do
			local hero = heroManager:getHero(enemy.count)
			if not hero.dead and hero.visible and hero.health and hero ~= nil and hero.team ~= myHero.team then
				QDmg = getDmg("Q", hero, myHero)
				WDmg = getDmg("W", hero, myHero)
				EDmg = getDmg("E", hero, myHero)
				if hero.health <= QDmg then
					enemy.timetokill = Q.CastTime
				else
					if myHero:CanUseSpell(_W) == READY then
						if hero.health <= WDmg then
							enemy.timetokill = W.CastTime
						elseif hero.health <= QDmg + WDmg then
							enemy.timetokill = Q.CastTime + W.CastTime
						end
						if myHero:CanUseSpell(_E) == READY then
							if hero.health <= QDmg + WDmg then
								enemy.timetokill = Q.CastTime + W.CastTime
							elseif hero.health <= QDmg + WDmg + EDmg then
								enemy.timetokill = Q.CastTime + W.CastTime + E.CastTime
							elseif hero.health <= (QDmg + WDmg + EDmg + QDmg) then
								enemy.timetokill = Q.CastTime + W.CastTime + E.CastTime + Q.Cooldown - 2000 + Q.CastTime
							elseif hero.health <= (QDmg + WDmg + QDmg + EDmg + QDmg) then
								enemy.timetokill = Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime + E.CastTime + Q.Cooldown - 1000 + Q.CastTime
							else
								enemy.timetokill = (Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime + E.CastTime + Q.Cooldown - 1000 + Q.CastTime) * math.floor(hero.health/(QDmg + WDmg + QDmg + EDmg + QDmg))
							end
						else
							if hero.health <= QDmg + WDmg then
								enemy.timetokill = Q.CastTime + W.CastTime
							elseif hero.health <= QDmg + WDmg + QDmg then
								enemy.timetokill = Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime
							else
								enemy.timetokill = (Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime) + ((Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime + E.CastTime + Q.Cooldown - 1000 + Q.CastTime) * math.floor(hero.health/(QDmg + WDmg + QDmg + EDmg + QDmg)))
							end
						end
					else
						if Q.Level >= 1 and W.Level >= 1 and E.Level >= 1 then
							enemy.timetokill = (Q.CastTime + E.CastTime + Q.Cooldown - 1000 + Q.CastTime) + ((Q.CastTime + W.CastTime + Q.Cooldown - 1000 + Q.CastTime + E.CastTime + Q.Cooldown - 1000 + Q.CastTime) * math.floor(hero.health/(QDmg + WDmg + QDmg + EDmg + QDmg)))
						else
							enemy.timetokill = 0
						end
					end
				end
			end
		end
	end
end

function Harass()
	if Config.MTM.MTMHarassT then
		myHero:MoveTo(mousePos.x, mousePos.z)
	end
	if myHero:CanUseSpell(_Q) == READY and validTarget(ts.target) then CastSpell(_Q, ts.target) end
end

function getBestCombo()
	if GetDistance(myHero, ts.target) <= 600 and validTarget(ts.target) then
		if (CountEnemys(R.SplashRange) >= 2 and Config.Basic.Ult) or Config.Basic.SingleUlt then
			if myHero:CanUseSpell(_Q) == READY then CastSpell(_Q, ts.target) end
			if myHero:CanUseSpell(_R) == READY then _CastSpell(_R) end
		end
		
		if GetDistance(ts.target, myHero) > Config.Basic.SafeDistance and not underTurret() then
			if (myHero.cdr * -1) > (Config.Basic.LongCDR/100) and ts.target.health > BurstDamage() then
				DoT()
			else
				Burst()
			end
		else
			FleeingCombo()
		end
		
		--[[if ((myHero:CanUseSpell(_Q) == COOLDOWN or myHero:CanUseSpell(_Q) == NOTLEARNED) and (myHero:CanUseSpell(_W) == COOLDOWN or myHero:CanUseSpell(_E) == NOTLEARNED) and (myHero:CanUseSpell(_E) == COOLDOWN or myHero:CanUseSpell(_W) == NOTLEARNED)) then
			myHero:Attack(target)
			--bugsplatting atm, enable later
		end]]
	end
end

function autoIgnite()
	for _, enemy in ipairs(enemies) do
		local hero = heroManager:getHero(enemy.count)
		if not hero.dead and hero.visible and hero.health and GetDistance(myHero, hero) <= 600 and hero.team ~= myHero.team then
			if hero.health <= Ignite.Damage then
				if hero.health <= getDmg("Q", hero, myHero, 3) and myHero:CanUseSpell(_Q) == READY then
					CastSpell(_Q, hero)
				elseif hero.health <= BurstDamage() and myHero:CanUseSpell(_W) == READY and myHero:CanUseSpell(_E) == READY then
					Burst()
				else
					_CastSpell(Ignite.Slot, hero)
				end
			end
		end
	end
end

function useActiveItems()
	if validTarget(ts.target) then
		if Config.Items.dfg and GetInventorySlotItem(activeItems.DFG) ~= nil then
			CastSpell(GetInventorySlotItem(activeItems.DFG), ts.target)
		end
		if Config.Items.hxg then
			if GetInventorySlotItem(activeItems.HXG) ~= nil then
				CastSpell(GetInventorySlotItem(activeItems.HXG), ts.target)
			elseif GetInventorySlotItem(activeItems.BWC) ~= nil then
				CastSpell(GetInventorySlotItem(activeItems.BWC), ts.target)
			end
		end
	end
end

function useActiveProc()
	if validTarget(ts.target) then
		if Config.Items.lb and (GetInventorySlotItem(activeItems.LICHBANE) ~= nil or GetInventorySlotItem(activeItems.SHEEN) ~= nil or GetInventorySlotItem(activeItems.ICEBORN) or GetInventorySlotItem(activeItems.TRINITY) ~= nil) then
			if GetDistance(ts.target, myHero) <= (myHero.range + GetDistance(myHero.minBBox)-10) then
				myHero:Attack(ts.target)
			end
		end
		if Config.Items.muramana and GetInventorySlotItem(activeItems.MURAMANA) ~= nil then
			MuramanaToggle(1000, ((myHero.mana / myHero.maxMana) > (Items.minmanamura / 100)))
		end
	end
end

function Farm()
	for _, minion in ipairs(minionManager(MINION_ENEMY, 600 , myHero, MINION_SORT_HEALTH_ASC).objects) do
		if validTarget(minion, QRange) then
			if Config.Farming.AAfarm then
				if minion.health < getDmg("AD", minion, myHero) then 
					myHero:Attack(minion)
				end
			end
			
			if Config.Farming.Qfarm then
				if myHero:CanUseSpell(_Q) == READY and minion.health < getDmg("Q", minion, myHero) then
					CastSpell(_Q, minion)
				end
			end
			
			if Config.Farming.QWEfarm then
				if myHero:CanUseSpell(_Q) == READY and minion.health < getDmg("Q", minion, myHero) then
					CastSpell(_Q, minion)
				elseif myHero:CanUseSpell(_W) == READY and minion.health < getDmg("W", minion, myHero) then
					CastSpell(_W, minion)
				elseif myHero:CanUseSpell(_E) == READY and minion.health < getDmg("E", minion, myHero) then
					CastSpell(_E, minion)
				end
			end
		end
	end
end

function OnTick()
	if not myHero.dead then
		ts:update()	
		UpdateSpells()
		if Config.Exploits.PassiveExploit.PE then
			PassiveExploit()
		end
		
		UpdateTimers()
		if validTarget(ts.target) then
			if Config.Basic.Harass then
				Harass()
				useActiveProc()
			elseif Config.Basic.Combo then
				useActiveItems()
				getBestCombo()
				if Config.MTM.MTMComboT then
					myHero:MoveTo(mousePos.x, mousePos.z)
				end
				useActiveProc()
			end
		end
		if Config.Basic.Ignite then
			autoIgnite()
		end
		if Config.Farming.Farm and not ((Config.Basic.Harass and Config.Farming.harassFarm) or (Config.Basic.Combo and Config.Farming.comboFarm)) then
			Farm()
		end
		if (Config.Basic.Harass and Config.MTM.MTMHarass) or (Config.Basic.Combo and Config.MTM.MTMCombo) then
			myHero:MoveTo(mousePos.x, mousePos.z)
		end
	end
end

function OnDraw()
	if Config.Drawing.DrawText then
		for _, enemy in ipairs(enemies) do
			local hero = heroManager:getHero(enemy.count)
			if hero.visible and not hero.dead and hero.team ~= myHero.team then
				heroPos = WorldToScreen(hero.pos)
				if enemy.timetokill ~= 0 then 
					timer = string.format("%.1f", (enemy.timetokill)/1000)
					DrawText(timer .. "s", 16, heroPos.x, heroPos.y, 0xFF80FF00)
				else
					DrawText("Not killable", 16, heroPos.x, heroPos.y, 0xFF80FF00)
				end
			end
		end
	end
	
	if Config.Drawing.DrawAATarget and attackTarget and attackTarget.valid then
		DrawCircle(attackTarget.x, attackTarget.y, attackTarget.z, 70, 0x000066FF)
	end
	
	
	if Config.Drawing.DrawProjectiles and Object and Object.valid then
		DrawCircle(Object.x, Object.y, Object.z, 60, 0xFF3D4F3D)
	end
	
	if validTarget(ts.target) then
		if Config.Drawing.DrawTarget then
			DrawCircle(ts.target.x, ts.target.y, ts.target.z, 70, 0x00FF0000)
		end
		if Config.Drawing.DrawSplashRange then
			DrawCircle(ts.target.x, ts.target.y, ts.target.z, R.SplashRange, 0x0066A3FF)
		end
	end
	
	if Config.Drawing.DrawSkillRange then
		if Q.Range == W.Range and W.Range == E.Range then
			DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, Q.Range, 0x000066FF)
		elseif Q.Range == E.Range or W.Range == E.Range then
			DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, Q.Range, 0x000066FF)
			DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, W.Range, 0x000066FF)
		elseif Q.Range == W.Range then
			DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, Q.Range, 0x000066FF)
			DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, E.Range, 0x000066FF)
		else
			DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, Q.Range, 0x000066FF)
			DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, W.Range, 0x000066FF)
			DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, E.Range, 0x000066FF)
		end
	end
	
	if Config.Drawing.DrawAARange then
		DrawCircle(myHero.pos.x, myHero.pos.y, myHero.pos.z, myHero.range + GetDistance(myHero.minBBox)-10, 0x000066FF)
	end
end