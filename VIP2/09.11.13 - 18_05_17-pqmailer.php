<?php exit() ?>--by pqmailer 217.82.28.169
class 'Plugin'
if not VIP_USER or myHero.charName ~= "TwistedFate" then return end
local Skills, Keys, Items, Data, Jungle, Helper, MyHero, Minions, Crosshair, Orbwalker = AutoCarry.Helper:GetClasses()
local Cards = {Red = false, Gold = false, Blue = false, PickRed = false, PickGold = false, PickBlue = false, Picked = nil, Timestamp = os.clock(), Tracker = false, Stacked = false}
local Sheen, Lichbane, Recall, Teleport = false, false, false, false

function Plugin:__init()
	PrintChat("<font color='#CCCCCC'>Twisted Fate by PQMailer loaded (Version 0.2)</font>")
	PrintChat("<font color='#CCCCCC'>Cheater's just a fancy word for winners</font>")
end

function Plugin:OnTick()
	if Menu.combo.useQ then
		AutoCarry.Skills:GetSkill(_Q).Enabled = true
	else
		AutoCarry.Skills:GetSkill(_Q).Enabled = false
	end

	if Menu.combo.extra.castRMouseOver then
		if myHero:CanUseSpell(_R) == READY and Teleport then
			CastSpell(_R, mousePos.x, mousePos.z)
		elseif myHero:CanUseSpell(_R) == READY and not Teleport then
			CastSpell(_R)
		end
	end

	if Menu.combo.extra.useQsnare and myHero:CanUseSpell(_Q) == READY and Recall == false then
		for _, Enemy in pairs(Helper.EnemyTable) do
			if ValidTarget(Enemy, 1450) and not Enemy.canMove then
				CastSpell(_Q, Enemy.x, Enemy.z)
			end
		end
	end

	if Menu.combo.extra.pickGoldCard then
		Cards.PickGold = true
		Cards.PickRed = false
		Cards.PickBlue = false
	elseif Menu.combo.extra.pickRedCard then
		Cards.PickGold = false
		Cards.PickRed = true
		Cards.PickBlue = false
	elseif Menu.combo.extra.pickBlueCard then
		Cards.PickGold = false
		Cards.PickRed = false
		Cards.PickBlue = true
	end

	if Cards.PickRed or Cards.PickGold or Cards.PickBlue then
		if Cards.Picked == "Gold" then
			Cards.PickGold = false
		elseif Cards.Picked == "Red" then
			Cards.PickRed = false
		elseif Cards.Picked == "Blue" then
			Cards.PickBlue = false
		end

		if myHero:CanUseSpell(_W) == READY and not Cards.Tracker then
			CastSpell(_W)
		end

		if Cards.PickGold and Cards.Gold then
			CastSpellEx(_W)
			return
		elseif Cards.PickRed and Cards.Red then
			CastSpellEx(_W)
			return
		elseif Cards.PickBlue and Cards.Blue then
			CastSpellEx(_W)
			return
		end
	end

	if Keys.AutoCarry then
		local Target = Crosshair:GetTarget()

		if ValidTarget(Target, 1450) then
			if myHero:CanUseSpell(_W) == READY and not Cards.Tracker then
				CastSpell(_W)
			end

			if Cards.Tracker then
				local Count = self:CountEnemies(Target, 100)
				if Cards.Blue and Menu.combo.useBlue and not self:CheckMana(Menu.combo.useBlueSlider) then
					CastSpellEx(_W)
					return
				end
				if Cards.Red and Menu.combo.useRed then
					if Menu.combo.useBlue and not self:CheckMana(Menu.combo.useBlueSlider) then
						return
					end
					if Count >= Menu.combo.useRedSlider then
						CastSpellEx(_W)
						return
					end
				end
				if Cards.Gold then
					if Menu.combo.useBlue and not self:CheckMana(Menu.combo.useBlueSlider) then
						return
					end
					if Menu.combo.useRed and Count >= Menu.combo.useRedSlider then
						return
					end
					CastSpellEx(_W)
					return
				end
			end
		end
	end
	if (Keys.MixedMode and (Menu.farm.mixedmode.useBlueMM or Menu.farm.mixedmode.useRedMM)) or (Keys.LaneClear and (Menu.farm.laneclear.useBlueLC or Menu.farm.laneclear.useRedLC)) and not Cards.Tracker then
		for _, Minion in pairs(Minions.EnemyMinions.objects) do
			if ValidTarget(Minion, 525) then
				CastSpell(_W)
			end
		end
	end
	if Cards.Tracker then
		if os.clock() > Cards.Timestamp + 7 then
			CastSpellEx(_W)
			return
		end
		if Keys.MixedMode then
			if Cards.Blue and Menu.farm.mixedmode.useBlueMM then
				CastSpellEx(_W)
				return
			end
		end
		if Keys.LaneClear then
			if Cards.Blue and Menu.farm.laneclear.useBlueLC and not self:CheckMana(Menu.farm.laneclear.useBlueLCSlider) then
				CastSpellEx(_W)
				return
			end
			if Cards.Red and Menu.farm.laneclear.useRedMM then
				if Menu.farm.laneclear.useBlueLC and not self:CheckMana(Menu.farm.laneclear.useBlueLCSlider) then
					return
				end
				CastSpellEx(_W)
				return
			end
		end
	end
end

function Plugin:OnCreateObj(obj)
	if obj.valid and myHero:GetDistance(obj) < 50 then
		if obj.name:find("Card_Yellow.troy") then
			Cards.Gold = true
		elseif obj.name:find("Card_Red.troy") then
			Cards.Red = true
		elseif obj.name:find("Card_Blue.troy") then
			Cards.Blue = true
		end
	end
end

function Plugin:OnDeleteObj(obj)
	if obj.valid and myHero:GetDistance(obj) < 50 then
		if obj.name:find("Card_Yellow.troy") then
			Cards.Gold = false
		elseif obj.name:find("Card_Red.troy") then
			Cards.Red = false
		elseif obj.name:find("Card_Blue.troy") then
			Cards.Blue = false
		end
	end
end

function Plugin:OnDraw()
	if Menu.draw.drawR and myHero:CanUseSpell(_R) == READY then
		DrawCircleMinimap(myHero.x, myHero.y, myHero.z, 5500, 1 ,CCCCCC, Menu.draw.drawRQuality*10)
	end
	if Menu.draw.drawLeftHealth then
		for _, Enemy in pairs(Helper.EnemyTable) do
			if ValidTarget(Enemy) then
				local Dmg = getDmg("Q", Enemy, myHero) + getDmg("W", Enemy, myHero) + getDmg("AD", Enemy, myHero)
				if Cards.Stacked then Dmg = Dmg + getDmg("E", Enemy, myHero) end
				if Sheen then Dmg = Dmg + getDmg("SHEEN", Enemy, myHero) end
				if Lichbane then Dmg = Dmg + getDmg("LICHBANE", Enemy, myHero) end

				DrawText3D(tostring(math.round(Enemy.health - Dmg)), Enemy.x, Enemy.y, Enemy.z, 16, ARGB(255, 255, 10, 20), true)
			end
		end
	end
end

function Plugin:OnProcessSpell(unit, spell)
	if unit.isMe and spell.name == "gate" and Menu.combo.extra.pickGoldOnR then
		Cards.PickGold = true
	end
end

function Plugin:CheckMana(percent)
	if (myHero.mana/myHero.maxMana)*100 >= percent then
		return true
	else
		return false
	end
end

function Plugin:CountEnemies(Point, Range)
	local Count = 0
	for _, Enemy in pairs(Helper.EnemyTable) do
		if ValidTarget(Enemy) and GetDistance(Enemy, Point) <= Range then
			Count = Count + 1
		end
	end

	return Count
end

AdvancedCallback:bind('OnGainBuff', function(unit, buff)
	if unit.isMe then
		if buff.name == "pickacard_tracker" then
			Cards.Tracker = true
			Cards.Timestamp = os.clock()
		elseif buff.name == "goldcardpreattack" then
			Cards.Picked = "Gold"
		elseif buff.name == "redcardpreattack" then
			Cards.Picked = "Red"
		elseif buff.name == "bluecardpreattack" then
			Cards.Picked = "Blue"
		elseif buff.name == "cardmasterstackholder" then
			Cards.Stacked = true
		elseif buff.name == "sheen" then
			Sheen = true
		elseif buff.name == "lichbane" then
			Lichbane = true
		elseif buff.name == "destiny_marker" then
			Teleport = true
		end
	end
end)

AdvancedCallback:bind('OnLoseBuff', function(unit, buff)
	if unit.isMe then
		if buff.name == "pickacard_tracker" then
			Cards.Tracker = false
		elseif buff.name == "goldcardpreattack" or buff.name == "redcardpreattack" or buff.name == "bluecardpreattack" then
			Cards.Picked = nil
		elseif buff.name == "cardmasterstackholder" then
			Cards.Stacked = false
		elseif buff.name == "sheen" then
			Sheen = false
		elseif buff.name == "lichbane" then
			Lichbane = false
		elseif buff.name == "destiny_marker" then
			Teleport = false
		end
	end
end)

AdvancedCallback:bind('OnRecall', function(unit, time)
	if unit.isMe then Recall = true end
end)

AdvancedCallback:bind('OnAbortRecall', function(unit)
	if unit.isMe then Recall = false end
end)

AdvancedCallback:bind('OnFinishRecall', function(unit)
	if unit.isMe then Recall = false end
end)

AutoCarry.Plugins:RegisterBonusLastHitDamage(function(minion)
	local BonusTotal = 0
	local DamageE = math.floor(30+(25*myHero:GetSpellData(_E).level)+(myHero.ap*0.4))
	local DamageGOLD = math.floor((7.5*(myHero:GetSpellData(_W).level)+7.5+(0.4*myHero.ap)+myHero.damage)+0.5)
	local DamageRED = math.floor((15*(myHero:GetSpellData(_W).level)+15+(0.4*myHero.ap)+myHero.damage)+0.5)
	local DamageBLUE = math.floor((20*(myHero:GetSpellData(_W).level)+20+(0.4*myHero.ap)+myHero.damage)+0.5)
	if Cards.Stacked then BonusTotal = BonusTotal + DamageE end
	if Cards.Picked == "Gold" then BonusTotal = BonusTotal + DamageGOLD end
	if Cards.Picked == "Red" then BonusTotal = BonusTotal + DamageRED end
	if Cards.Picked == "Blue" then BonusTotal = BonusTotal + DamageBLUE end
	if Sheen then BonusTotal = BonusTotal + (GetInventorySlotItem(3057) and getDmg("SHEEN", minion, myHero) or 0) end
	if Lichbane then BonusTotal = BonusTotal + (GetInventorySlotItem(3100) and getDmg("LICHBANE", minion, myHero) or 0) end

	return BonusTotal
end)

Menu = AutoCarry.Plugins:RegisterPlugin(Plugin(), "PQTwistedFate")
Menu:addSubMenu("Combo settings", "combo")
Menu.combo:addParam("useQ","Use Wild Cards in combo", SCRIPT_PARAM_ONOFF, true)
Menu.combo:addParam("useRed", "Pick red card in combo", SCRIPT_PARAM_ONOFF, true)
Menu.combo:addParam("useRedSlider", "Enemy count for red card", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
Menu.combo:addParam("useBlue", "Pick blue card in combo", SCRIPT_PARAM_ONOFF, true)
Menu.combo:addParam("useBlueSlider", "Min. mana % for blue card", SCRIPT_PARAM_SLICE, 15, 0, 100, 0)
Menu.combo:addSubMenu("Extra settings", "extra")
Menu.combo.extra:addParam("useQsnare","Use Wild Cards on inmobalized targets", SCRIPT_PARAM_ONOFF, true)
Menu.combo.extra:addParam("pickGoldOnR","Pick gold card when teleporting", SCRIPT_PARAM_ONOFF, true)
Menu.combo.extra:addParam("castRMouseOver", "Cast R to mouse over", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("T"))
Menu.combo.extra:addParam("pickGoldCard", "Pick gold card", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("E"))
Menu.combo.extra:addParam("pickRedCard", "Pick red card", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("Y"))
Menu.combo.extra:addParam("pickBlueCard", "Pick blue card", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("G"))
Menu:addSubMenu("Farm settings", "farm")
Menu.farm:addSubMenu("Mixed Mode", "mixedmode")
Menu.farm.mixedmode:addParam("useBlueMM", "Pick blue card in mixed mode", SCRIPT_PARAM_ONOFF, true)
Menu.farm:addSubMenu("Lane Clear", "laneclear")
Menu.farm.laneclear:addParam("useRedLC", "Pick red card while lane clear", SCRIPT_PARAM_ONOFF, true)
Menu.farm.laneclear:addParam("useBlueLC", "Pick blue card while lane clear", SCRIPT_PARAM_ONOFF, true)
Menu.farm.laneclear:addParam("useBlueLCSlider", "Min. mana % for blue card", SCRIPT_PARAM_SLICE, 50, 0, 100, 0)
Menu:addSubMenu("Draw settings", "draw")
Menu.draw:addParam("drawR", "Draw R range on minimap", SCRIPT_PARAM_ONOFF, true)
Menu.draw:addParam("drawRQuality", "Circle quality", SCRIPT_PARAM_SLICE, 1, 1, 10, 0)
Menu.draw:addParam("drawLeftHealth", "Draw left health after Q->W->AA", SCRIPT_PARAM_ONOFF, true)